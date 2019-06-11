/*
DROP TABLE Minds;
/
DROP TYPE NeuralNetwork;
/
DROP TYPE INTEGER_TABLE;
/
DROP TYPE STRING_TABLE;
/
DROP TYPE MATRIX;
/
DROP TYPE ARRAY_2D;
/
DROP TYPE ARRAY_1D;
/
DROP TYPE MapStringInt;
/
DROP TYPE PairStringIntTable;
/
DROP TYPE PairStringInt;
/
DROP PACKAGE Math;
/
*/

/*
 * Compilation order: 
 *  Math.sql -> Matrix.sql -> Map.sql -> NeuralNetwork.sql
 */


CREATE OR REPLACE TYPE STRING_TABLE IS TABLE OF VARCHAR(10000);
/ 

CREATE OR REPLACE TYPE INTEGER_TABLE IS TABLE OF INTEGER;
/

CREATE OR REPLACE TYPE NeuralNetwork AS OBJECT
(
  /*
   * number of neurons in the hidden layer
   */
  noOfNeurons_HL INTEGER,
  xTrain MATRIX,
  yTrain MATRIX,
  /*
   * weights matrix for the input -> hidden layer
   */
  weights_ih MATRIX,
  /*
   * weights matrix for the hidden -> output layer
   */
  weights_ho MATRIX,
  
  /*
   * size of the vocabulary (i.e no. of unique words) in our corpus
   */
  corpusVocabSize INTEGER,
  /*
   * all words in our given corpus
   */
  corpusWords MapStringInt,
  /*
   * a mapping from String -> Integer that counts the no. of occurrences 
   * of each word in our corpus
   */
  wordCountMap MapStringInt,
  
  /*
   * the rate of learning of our Neural Network, which is applied as 
   * a modifier to the rate of prediction error correction
   */
  learningRate FLOAT,
  /*
   * the size of the corpus window (how many surrounding words are 
   * taken into context considering a center word)
   */
  windowSize INTEGER,
  loss FLOAT,
  h MATRIX,
  u MATRIX,
  y MATRIX,
  
  CONSTRUCTOR FUNCTION NeuralNetwork RETURN SELF AS RESULT,
  MEMBER PROCEDURE setH(h MATRIX),
  MEMBER PROCEDURE setU(u MATRIX),
  MEMBER PROCEDURE setY(y MATRIX),
  MEMBER FUNCTION processCorpus(corpus VARCHAR) RETURN STRING_TABLE,
  MEMBER PROCEDURE feedForward(x MATRIX, retY IN OUT MATRIX),
  MEMBER PROCEDURE backPropagation(x MATRIX, expectedY MATRIX),
  MEMBER PROCEDURE preTraining(sentences STRING_TABLE),
  MEMBER PROCEDURE training(noOfEpochs INT),
  MEMBER PROCEDURE predict(word VARCHAR, noOfPredictions INT, predictions IN OUT STRING_TABLE)
  
);
/

CREATE OR REPLACE TYPE BODY NeuralNetwork AS
  /**
   * construct an object of type 'NeuralNetwork'
   * @return an initialized Neural Network object 
   */
  CONSTRUCTOR FUNCTION NeuralNetwork RETURN SELF AS RESULT AS
  BEGIN
    SELF.noOfNeurons_HL := 5;
    /*
     * initialize xTrain and yTrain 
     */ 
    SELF.xTrain := MATRIX();
    SELF.yTrain := MATRIX();
    
    SELF.windowSize := 2;
    SELF.learningRate := 0.01;
  
    SELF.corpusWords := MapStringInt();
    SELF.wordCountMap := MapStringInt();
    
    RETURN;
  END NeuralNetwork;
  
  MEMBER PROCEDURE setH(h MATRIX) AS
  BEGIN
    SELF.h := h;
  END setH;
  
  MEMBER PROCEDURE setU(u MATRIX) AS
  BEGIN
    SELF.u := u;
  END setU;
  
  MEMBER PROCEDURE setY(y MATRIX) AS
  BEGIN
    SELF.y := y;
  END setY;
  
  /**
   * processes given corpus and collect the inner sentences
   * @param corpus the corpus containing our training data
   * @return the string table containing the sentences found in the corpus
   */
  MEMBER FUNCTION processCorpus(corpus VARCHAR) RETURN STRING_TABLE AS
  corpusSentences STRING_TABLE;
  BEGIN
    corpusSentences := STRING_TABLE();
    
    /*
     * split corpus sentences and collect them into our 'corpusSentences'
     * variable
     */
    SELECT REGEXP_SUBSTR(corpus, '[^\.]+', 1, LEVEL) 
      BULK COLLECT INTO corpusSentences FROM DUAL CONNECT BY
      REGEXP_SUBSTR(corpus, '[^\.]+', 1, LEVEL) IS NOT NULL;
      
    RETURN corpusSentences;
  END processCorpus;
  
  MEMBER PROCEDURE preTraining(sentences STRING_TABLE) AS
  trainingData MapStringInt;
  words STRING_TABLE;
  vocabulary MapStringInt;
  BEGIN
    trainingData := MapStringInt();
    words := STRING_TABLE();
    vocabulary := MapStringInt();
    
    FOR i IN sentences.FIRST .. sentences.LAST LOOP
      SELECT LOWER(REGEXP_SUBSTR(sentences(i), '[^ ]+', 1, LEVEL))
        BULK COLLECT INTO words FROM DUAL CONNECT BY
        LOWER(REGEXP_SUBSTR(sentences(i), '[^ ]+', 1, LEVEL)) IS NOT NULL;
        
      FOR j IN 1 .. words.COUNT LOOP
        BEGIN
          IF trainingData.getVal(words(j)) IS NOT NULL THEN
            trainingData.setVal(words(j), trainingData.getVal(words(j)) + 1);
          ELSE
            trainingData.addNewPair(PairStringInt(words(j), 1));
          END IF;
        END;
      END LOOP;
    END LOOP;
    
    SELF.corpusVocabSize := trainingData.data.COUNT;
    
    FOR i IN 1 .. trainingData.data.COUNT LOOP
      vocabulary.addNewPair(PairStringInt(trainingData.data(i).fst, i));
    END LOOP;
    
    FOR j IN 1 .. trainingData.data.COUNT LOOP
      DECLARE
        centerWord ARRAY_1D := ARRAY_1D();
        wordContext ARRAY_1D := ARRAY_1D();
      BEGIN
        /*
         * generate a One-Hot encoding of each center word and their
         * context words
         */
        centerWord := Math.createVector(SELF.corpusVocabSize, 0.0);
        centerWord(vocabulary.getVal(trainingData.data(j).fst)) := 1.0;
          
        wordContext := Math.createVector(SELF.corpusVocabSize, 0.0);
          
        /*
         * encode the context words inside the current window
         */
        FOR k IN (j - SELF.windowSize) .. (j + SELF.windowSize) LOOP
          IF j <> k AND k >= 1 AND k < trainingData.data.COUNT THEN
            wordContext(vocabulary.getVal(trainingData.data(k).fst)) := 
              wordContext(vocabulary.getVal(trainingData.data(k).fst)) + 1;
          END IF;
        END LOOP;
          
        /*
         * append the current center word col. vector to xTrain matrix
         * and the current center word's context col. vector to yTrain matrix
         */
        SELF.xTrain.appendColVector(centerWord);
        SELF.yTrain.appendColVector(wordContext);
      END;
    END LOOP;
    
    /*
     * the weights matrix for the input -> hidden layer ought to be
     * of size (corpusVocabSize x noOfNeurons_HL)
     */
    SELF.weights_IH := MATRIX(SELF.corpusVocabSize, SELF.noOfNeurons_HL);
    SELF.weights_IH.randomizeMatrix(-1.0, 1.0);
    
    /*
     * the weights matrix for the hidden -> output layer ought to be
     * of size (noOfNeurons_HL x corpusVocabSize)
     */
    SELF.weights_HO := MATRIX(SELF.noOfNeurons_HL, SELF.corpusVocabSize);
    SELF.weights_HO.randomizeMatrix(-1.0, 1.0);
    
    SELF.corpusWords := trainingData;
    
    FOR i IN 1 .. SELF.corpusWords.data.COUNT LOOP
      SELF.wordCountMap.addNewPair(PairStringInt(SELF.corpusWords.data(i).fst, i));
    END LOOP;
  END preTraining;
  
  /**
   * applies the Feed Forward algorithm to the given input 'x'
   * @param x the column vector that contains all inputs
   * @return the prediction of the Neural Network based on its curr. ability
   */
  MEMBER PROCEDURE feedForward(x MATRIX, retY IN OUT MATRIX) AS
  h MATRIX;
  u MATRIX;
  y MATRIX;
  BEGIN
    /*
     * compute W_IH.T . x (which will give a col. vector of size
     * (noOfNeurons_HL, 1))
     */
    h := MATRIX.manipulate(MATRIX.transpose(SELF.weights_ih), x, 'mul');
    /*
     * compute W_HO.T . h (which will give a col. vector of size
     * (corpusVocabSize, 1))
     */
    u := MATRIX.manipulate(MATRIX.transpose(SELF.weights_ho), h, 'mul');
    /*
     * apply softmax() to the previously computed col. vector
     */
    y := MATRIX.colVectorSoftmax(u);
    
    SELF.setH(h);
    SELF.setU(u);
    SELF.setY(y);
    
    IF retY IS NOT NULL THEN
      retY := y;
    END IF;
  END feedForward;
  
  /**
   * apply the Back Propagation algorithm, taking into account the
   *    results of the previous Feed Forward, to attempt to correct the
   *    Neural Network's errors/failure
   * @param x the previously given input that resulted the Neural 
   *    Network's curr. state
   * @param expectedY the col. vector representing the expected 
   *    (the true) result that the Neural Network ought to have had
   */
  MEMBER PROCEDURE backPropagation(x MATRIX, expectedY MATRIX) AS
  errors MATRIX;
  tweaked_weights_IH MATRIX;
  tweaked_weights_HO MATRIX;
  BEGIN
    /*
     * compute the errors col. vector: (y - expectedY)
     */
    errors := MATRIX.manipulate(SELF.y, expectedY, 'sub');
    
    /*
     * correct the W_HO: Corr_W_HO = (h . e.T)
     */
    tweaked_weights_HO := MATRIX.manipulate(SELF.h, 
      MATRIX.transpose(errors), 'mul');
    /*
     * correct the W_IH: Corr_W_IH = (x . (W_HO . e).T)
     */
    tweaked_weights_IH := MATRIX.manipulate(x,
      MATRIX.transpose(MATRIX.manipulate(SELF.weights_HO, errors, 'mul')),
      'mul');
    
    /*
     * apply W_HO correction using the 'learningRate' modifier:
     * (W_HO - lr * Corr_W_HO)
     */
    SELF.weights_HO := MATRIX.manipulate(SELF.weights_HO, 
      MATRIX.manipulate(tweaked_weights_HO, SELF.learningRate, 'mul'),
      'sub');
    /*
     * apply W_IH correction using the 'learningRate' modifier:
     * (W_IH - lr * Corr_W_IH)
     */
    SELF.weights_IH := MATRIX.manipulate(SELF.weights_IH,
      MATRIX.manipulate(tweaked_weights_IH, SELF.learningRate, 'mul'),
      'sub');
  END backPropagation;
  
  /**
   * train the neural network for a given number of epochs
   *    i.e - apply feed forward and then back propagation to fix 
   *    errors a certain amount of times
   * @param noOfEpochs the number of epochs to train the neural network
   */
  MEMBER PROCEDURE training(noOfEpochs INT) AS
  noOfContextWords INT := 0;
  y MATRIX;
  xTrain_colVec ARRAY_1D := ARRAY_1D();
  yTrain_colVec ARRAY_1D := ARRAY_1D();
  BEGIN
    xTrain_colVec.EXTEND(SELF.xTrain.noOfRows);
    yTrain_colVec.EXTEND(SELF.yTrain.noOfRows);
  
    FOR i IN 1 .. noOfEpochs LOOP
      SELF.loss := 0.0;
      
      FOR j IN 1 .. SELF.xTrain.noOfCols LOOP
        FOR k IN 1 .. SELF.xTrain.noOfRows LOOP
          xTrain_colVec(k) := SELF.xTrain.data(k)(j);
        END LOOP;
        
        FOR k IN 1 .. SELF.yTrain.noOfRows LOOP
          yTrain_colVec(k) := SELF.yTrain.data(k)(j);
        END LOOP;
      
        feedForward(MATRIX.colVectorToMatrix(xTrain_colVec), y);
        backPropagation(MATRIX.colVectorToMatrix(xTrain_colVec), 
          MATRIX.colVectorToMatrix(yTrain_colVec));
        noOfContextWords := 0;
        
        FOR m IN 1 .. SELF.corpusVocabSize LOOP
          IF (SELF.yTrain.data(j)(m) = 1.0) THEN
            SELF.loss := SELF.loss + (-1) * SELF.u.data(m)(1);
            noOfContextWords := noOfContextWords +1;
          END IF;
        END LOOP;
        
        SELF.loss := SELF.loss + noOfContextWords * 
          LOG(2, MATRIX.elemSum(MATRIX.exp(SELF.u)));
          
        SELF.learningRate := SELF.learningRate * 
          (1 / (1 + SELF.learningRate * i));
      END LOOP;
      
      DBMS_OUTPUT.PUT_LINE('Epoch ' || i || ' loss: ' || SELF.loss 
        || '; learningRate: ' || SELF.learningRate);
    END LOOP;
  END training;
  
  MEMBER PROCEDURE predict(word VARCHAR, noOfPredictions INT, predictions IN OUT STRING_TABLE) AS
  wordIndex INTEGER;
  wordVec ARRAY_1D;
  wordVecMatrix MATRIX;
  nnOutput MATRIX := MATRIX();
  nnOutputTranslation MapStringInt := MapStringInt();
  foundContextWords STRING_TABLE := STRING_TABLE();
  BEGIN
    wordIndex := SELF.wordCountMap.getVal(word);
    DBMS_OUTPUT.PUT_LINE('wordIndex: ' || wordIndex);
    wordVec := ARRAY_1D();
    wordVec := Math.createVector(SELF.corpusVocabSize, 0.0);
    wordVec(wordIndex) := 1.0;
    
    wordVecMatrix := MATRIX.colVectorToMatrix(wordVec);
    feedForward(wordVecMatrix, nnOutput);
    
    FOR i IN 1 .. SELF.corpusVocabSize LOOP
      nnOutputTranslation.addNewPair(PairStringInt(nnOutput.data(i)(1), i));
    END LOOP;
    
    foundContextWords.EXTEND(1);
    
    FOR i IN REVERSE 1 .. nnOutputTranslation.data.COUNT LOOP
      foundContextWords(foundContextWords.COUNT) := 
        SELF.corpusWords.data(nnOutputTranslation.data(i).snd).fst;
        
      IF foundContextWords.COUNT >= noOfPredictions THEN
        EXIT;
      END IF;
      
      foundContextWords.EXTEND(1);
    END LOOP; 
    
    predictions := foundContextWords;
    
    EXCEPTION WHEN NO_DATA_FOUND THEN
      RAISE_APPLICATION_ERROR(-20004, 'The word whose context we are trying to predict is not in our dictionary!');
  END predict;
END;
/

/*
 * example of splitting sentences
 */
/*
SELECT LOWER(REGEXP_SUBSTR('Smith is a good guy. So is Jones. Maybe also Allen.', 
  '[^\.]+', 1, LEVEL)) FROM DUAL CONNECT BY (REGEXP_SUBSTR(
  'Smith is a good guy. So is Jones. Maybe also Allen.', '[^\.]+', 1,
  LEVEL)) IS NOT NULL;
*/

/*
 * example of splitting words
 */
/*
SELECT DISTINCT REGEXP_SUBSTR('SMITH   WARD   ALLEN    WARD     JONES', '[^ ]+', 1, LEVEL)
  FROM DUAL CONNECT BY REGEXP_SUBSTR('SMITH WARD ALLEN WARD JONES', '[^ ]+', 1, LEVEL)
  IS NOT NULL;
*/

CREATE TABLE Minds (
  nn NeuralNetwork
) NESTED TABLE nn.corpusWords.data STORE AS nn_corpusWords,
  NESTED TABLE nn.wordCountMap.data STORE AS nn_wordCountMap
;
/

/*
SELECT * FROM ALL_TYPES WHERE OWNER='STUDENT' AND TYPE_NAME='MATRIX';
SELECT * FROM ALL_TYPE_ATTRS WHERE TYPE_NAME='ARRAY_1D';
*/

/*
 * usage example
 */

SET SERVEROUTPUT ON;
DECLARE
  nn NeuralNetwork;
  predictions STRING_TABLE;
BEGIN

  nn := NeuralNetwork();
  nn.preTraining(
    nn.processCorpus(
      'The world is a small place.' ||
      'World war two was one of the most catastrophic events to ever take place.' ||
      'Many people fought in World War two.' ||
      'Many people would like to see the world burn.' ||
      'Not many people like to see this world.' ||
      'He can see that it was not that difficult.' ||
      'See that you succeed in this mission.' ||
      'What you see is not necessarily what he sees.' ||
      'See to it that he makes it.'
    ) 
  );
  nn.training(5);
  
  nn.predict('people', 5, predictions);
  
  DBMS_OUTPUT.PUT_LINE('Predictions: ');
  FOR i IN predictions.FIRST .. predictions.LAST LOOP
    DBMS_OUTPUT.PUT_LINE(predictions(i));
  END LOOP;  
  
  INSERT INTO Minds VALUES (nn);
  
END;
/

DECLARE
neuralNetwork Minds.nn%TYPE;
predictions STRING_TABLE;
BEGIN

  SELECT nn INTO neuralNetwork FROM Minds;
  
  neuralNetwork.predict('world', 5, predictions);
  
  DBMS_OUTPUT.PUT_LINE('Predictions: ');
  FOR i IN predictions.FIRST .. predictions.LAST LOOP
    DBMS_OUTPUT.PUT_LINE(predictions(i));
  END LOOP;  
  
END;
/
  
/*
 * test runs
 */
 /*
DECLARE
myTab STRING_TABLE := STRING_TABLE();
myMap MapStringInt := MapStringInt();
BEGIN
  myTab.EXTEND(5);
  
  myTab(1) := 'Alex';
  myTab(2) := 'Chris';
  myTab(3) := 'd';
  myTab(4) := 'e';
  myTab(5) := 'Ben';
  
  FOR i IN 1 .. myTab.COUNT LOOP
    DBMS_OUTPUT.PUT_LINE(myTab(i));
  END LOOP; 

  myMap.addNewPair(PairStringInt('Alex', 10));
  myMap.addNewPair(PairStringInt('Chris', 5));
  myMap.addNewPair(PairStringInt('Ben', 3));
  
  DBMS_OUTPUT.PUT_LINE(myMap.getVal(myTab(5)));
END;
/
*/