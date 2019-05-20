CREATE OR REPLACE PACKAGE Math IS
  /**
   * Euler's constant
   */
  euler FLOAT := 2.71828182846;
  
  TYPE stringToIntMap IS TABLE OF INTEGER INDEX BY VARCHAR(100);
  TYPE intToIntMap IS TABLE OF INTEGER INDEX BY PLS_INTEGER;
  
  FUNCTION exp (x FLOAT) RETURN FLOAT;
  FUNCTION maxElem (vector ARRAY_1D) RETURN FLOAT;
  FUNCTION sumOfElems (vector ARRAY_1D) RETURN FLOAT;
  
  FUNCTION randomVector (vectorSize INTEGER, minBound FLOAT, 
    maxBound FLOAT) RETURN ARRAY_1D;
  FUNCTION createVector(noOfElems INT, val FLOAT) RETURN ARRAY_1D;
  
  FUNCTION softmax (vector ARRAY_1D) RETURN ARRAY_1D;
END Math;
/

CREATE OR REPLACE PACKAGE BODY Math AS
  /**
   * returns the mathematical operation of 'e ^ x'
   * @param x the number that will specify what power to raise 'e' to
   * @return the computation of 'e power x'
   */
  FUNCTION exp (x FLOAT) RETURN FLOAT AS
  BEGIN
    RETURN euler ** x;
  END exp;
  
  /**
   * returns the maximum number from a vector
   * @param vector the vector that contains a series of numbers
   * @return the maximum number from the series of numbers inside the vector
   */
  FUNCTION maxElem (vector ARRAY_1D) RETURN FLOAT AS
  currMax FLOAT;
  BEGIN
    /*
     * exception treatment
     * if the size of the vector is zero, return null
     * if the size of the vector is one, return that one element
     */
    IF (vector.COUNT = 0) THEN
      RETURN NULL;
    ELSIF (vector.COUNT = 1) THEN
      RETURN vector(1);
    END IF;
  
    currMax := vector(1);
    
    FOR i IN 2 .. vector.COUNT LOOP
      IF (vector(i) > currMax) THEN
        currMax := vector(i);
      END IF;
    END LOOP;
    
    RETURN currMax;
  END maxElem;
  
  /**
   * returns the sum of all elements of a vector
   * @param vector the vector whose elements we have to add up
   * @return the sum of all elements of the given vector
   */
  FUNCTION sumOfElems (vector ARRAY_1D) RETURN FLOAT AS
  accumulator FLOAT;
  BEGIN
    /*
     * exception treatment
     * if the size of the vector is 0, then return null
     * if the size of the vector is 1, then return the first element
     */
    IF (vector.COUNT = 0) THEN
      RETURN NULL;
    ELSIF (vector.COUNT = 1) THEN
      RETURN vector(1);
    END IF;
    
    accumulator := vector(1);
    
    FOR i IN 2 .. vector.COUNT LOOP
      accumulator := accumulator + vector(i);
    END LOOP;
    
    RETURN accumulator;
  END sumOfElems;
  
  /**
   * generates a random vector w/ given parameters
   * @param vectorSize the size of the vector to be generated
   * @param minBound the lower bound for the numbers to be generated
   * @param maxBound the upper bound for the numbers to be generated
   * @return the randomly generated vector
   */
  FUNCTION randomVector (vectorSize INTEGER, minBound FLOAT, 
    maxBound FLOAT) RETURN ARRAY_1D AS
  vector ARRAY_1D;
  BEGIN
    IF (vectorSize <= 0) THEN
      RETURN null;
    END IF;
  
    vector := ARRAY_1D();
    vector.EXTEND(vectorSize);
    
    FOR i IN 1 .. vector.COUNT LOOP
      SELECT DBMS_RANDOM.VALUE(minBound, maxBound) INTO vector(i) FROM DUAL;
    END LOOP;
    
    RETURN vector;
  END randomVector;
  
  /**
   * create a vector of the given size, initialized w/ the value given
   * @param noOfElems the no. of elements the vector should have
   * @param val the value each data cell of the vector should have
   * @return the resulting vector
   */
  FUNCTION createVector(noOfElems INT, val FLOAT) RETURN ARRAY_1D AS
  vector ARRAY_1D;
  BEGIN
    IF noOfElems < 0 THEN
      RAISE_APPLICATION_ERROR(-20002, 'Cannot create a vector of negative size!');
    ELSIF noOfElems = 0 THEN
      RETURN null;
    END IF;
    
    vector := ARRAY_1D();
    vector.EXTEND(1);
    vector(1) := val;
    
    IF noOfElems = 1 THEN
      RETURN vector;
    END IF;
    
    /*
     * replicate the element on the position specified by the 2nd param.
     * the no. of times specified by the 1st param
     */
    vector.EXTEND(noOfElems - 1, 1);
    
    RETURN vector;
  END createVector;
  
  /**
   * a 'map' type of function that applies the softmax function to 
   * all vector elements
   * @param vector the vector to apply the mapping to
   * @return the vector after having applied the softmax mapping
   */
  FUNCTION softmax (vector ARRAY_1D) RETURN ARRAY_1D AS
  vectorMax FLOAT;
  vectorSum FLOAT; 
  newVector ARRAY_1D;
  BEGIN
    IF (vector.COUNT = 0) THEN
      RETURN NULL;
    END IF;
  
    vectorMax := maxElem(vector);
    vectorSum := sumOfElems(vector);
    
    newVector := ARRAY_1D();
    newVector.EXTEND(vector.COUNT);
  
    FOR i IN 1 .. newVector.COUNT LOOP
      newVector(i) := exp(vector(i) - vectorMax) / vectorSum;
    END LOOP;
    
    RETURN newVector;
  END softmax;
  
END Math;
/


/*
 * Math package usage application
 */
/*
SET SERVEROUTPUT ON;
DECLARE
  vector ARRAY_1D;
  vector_maxElem FLOAT;
  vector_sumOfElems FLOAT;
BEGIN
  vector := Math.randomVector(4, -0.8, 0.8);

  FOR i IN 1 .. vector.COUNT LOOP
    DBMS_OUTPUT.PUT(vector(i) || ' ');
  END LOOP;
  DBMS_OUTPUT.PUT_LINE('');
  
  vector_maxElem := Math.maxElem(vector);
  vector_sumOfElems := Math.sumOfElems(vector);
  
  DBMS_OUTPUT.PUT_LINE('vectors max element: ' || vector_maxElem);
  DBMS_OUTPUT.PUT_LINE('vectors sum of elems: ' || vector_sumOfElems);
  
  
  DBMS_OUTPUT.PUT_LINE('After softmax application: ');
  
  vector := Math.softmax(vector);
  
  FOR i IN 1 .. vector.COUNT LOOP
    DBMS_OUTPUT.PUT(vector(i) || ' ');
  END LOOP;
  DBMS_OUTPUT.PUT_LINE('');
END;
/
*/