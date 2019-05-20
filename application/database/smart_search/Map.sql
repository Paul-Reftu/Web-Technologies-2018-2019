/*
 * if the following types are to be replaced, then they must be 
 * either replaced in the reverse order due to the linear 1-directional
 * dependencies created OR they must first be dropped in the reverse order
 * and then created again
 */
/*
DROP TYPE MapStringInt;
/
DROP TYPE PairStringIntTable;
/
DROP TYPE PairStringInt;
/
*/

/***************** BEGIN Pair<String, Integer> **************/
CREATE OR REPLACE TYPE PairStringInt AS OBJECT
(
  fst VARCHAR(100),
  snd INTEGER,
  
  CONSTRUCTOR FUNCTION PairStringInt (fst VARCHAR, snd INTEGER) 
    RETURN SELF AS RESULT
);
/
/**
 * an object that represents a pair of <String, Integer>
 */
CREATE OR REPLACE TYPE BODY PairStringInt AS
  /**
   * constructs an object of type 'PairStringInt' and initializes
   *    the 'fst' and 'snd' attributes based on the given parameters
   * @param fst the first element of the tuple to insert
   * @param snd the second element of the tuple to insert
   * @return the initialized pair of type <String, Integer>
   */
  CONSTRUCTOR FUNCTION PairStringInt(fst VARCHAR, snd INTEGER) 
    RETURN SELF AS RESULT AS
  BEGIN
    SELF.fst := fst;
    SELF.snd := snd;
    
    RETURN;
  END PairStringInt;
END;
/

/***************** END Pair<String, Integer> **************/

/**
 * a table type that contains Pair<String, Integer> objects
 */
CREATE OR REPLACE TYPE PairStringIntTable AS TABLE OF PairStringInt;
/

/***************** BEGIN Map<String, Integer> **************/
CREATE OR REPLACE TYPE MapStringInt AS OBJECT
(
  data PairStringIntTable,
  
  CONSTRUCTOR FUNCTION MapStringInt RETURN SELF AS RESULT,
  MEMBER PROCEDURE sortData,
  MEMBER PROCEDURE addNewPair(pair PairStringInt),
  MEMBER FUNCTION getVal(searchKey VARCHAR) RETURN INTEGER,
  MEMBER PROCEDURE setVal(searchKey VARCHAR, newVal INTEGER)
  
  
);
/

/**
 * an object that represents a Map of type <String, Integer>
 */
CREATE OR REPLACE TYPE BODY MapStringInt AS
  /**
   * constructs an object of type Map<String, Integer>
   * @return the initialized object
   */
  CONSTRUCTOR FUNCTION MapStringInt RETURN SELF AS RESULT AS
  BEGIN
    SELF.data := PairStringIntTable();
    RETURN;
  END MapStringInt;
  
  /**
   * sorts the data inside the map according to the map keys
   */
  MEMBER PROCEDURE sortData AS
  TYPE AssocArrStringInt IS TABLE OF INTEGER INDEX BY VARCHAR(100);
  assocArr AssocArrStringInt;
  assocArr_currElem VARCHAR(100);
  sortedData PairStringIntTable := PairStringIntTable();
  BEGIN
    /*
     * loop through our object's data pairs and put them into our
     * temporary associative array for them to be auto-sorted
     */
    FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
      assocArr(SELF.data(i).fst) := SELF.data(i).snd;
    END LOOP;
    
    sortedData.EXTEND(assocArr.COUNT);
    
    /*
     * loop through the associative's array sorted key-value pairs
     * and put them in order into our temporary 'sortedData' table
     * of user-type Map<String, Integer> for a make-shift sorting
     * procedure
     */
    assocArr_currElem := assocArr.FIRST;
    FOR i IN 1 .. assocArr.COUNT LOOP
      sortedData(i) := PairStringInt(assocArr_currElem, assocArr(assocArr_currElem));
      assocArr_currElem := assocArr.NEXT(assocArr_currElem);
    END LOOP;
    
    /*
     * finally, clone the sortedData into our object's data to complete
     * the sorting algorithm
     */ 
    SELF.data := sortedData;
  END sortData;
  
  /**
   * add a new pair to our object's data, maintaining the pairs
   * sorted based on the their key component
   */
  MEMBER PROCEDURE addNewPair(pair PairStringInt) AS
  BEGIN
    SELF.data.EXTEND(1);
    SELF.data(SELF.data.COUNT) := pair;
    SELF.sortData();
  END addNewPair;
  
  /**
   * returns the value component of a corresponding key given as param.
   * @param searchKey the 'key' component to perform the search w/
   * @return the corresponding value, if it exists - and null otherwise
   */
  MEMBER FUNCTION getVal(searchKey VARCHAR) RETURN INTEGER AS
  BEGIN
    /*
     * Remark: Since our object's 'data' attribute is sorted, something
     * like a binary search should be done instead of linear search
     */
    FOR i IN 1 .. data.COUNT LOOP
      IF data(i).fst = searchKey THEN
        RETURN data(i).snd;
      END IF;
    END LOOP;
    
    RETURN null;
  END getVal;
  
  /**
   * sets a new value for the value component of key-value pair 
   *    corresponding to the given search key
   * @param searchKey the key to find the key-value pair whose value we must modify
   * @param newVal the new value component for the key-value pair in question
   */
  MEMBER PROCEDURE setVal(searchKey VARCHAR, newVal INTEGER) AS
  BEGIN
    FOR i IN 1 .. data.COUNT LOOP
      IF data(i).fst = searchKey THEN
        data(i).snd := newVal;
      END IF;
    END LOOP;
  END setVal;
END;
/
/***************** END Map<String, Integer> **************/



/*
 * example of usage
 */
/*
SET SERVEROUTPUT ON;
DECLARE
  myMap MapStringInt := MapStringInt();
  myPair PairStringInt := PairStringInt('Jack', 10);
BEGIN
  myMap.addNewPair(myPair);
  myMap.addNewPair(PairStringInt('Kain', 20));
  myMap.addNewPair(PairStringInt('Alex', 30));
  myMap.addNewPair(PairStringInt('Xayton', 40));
  myMap.addNewPair(PairStringInt('Moe', 50));
  myMap.addNewPair(PairStringInt('Chris', 60));
  
  FOR i IN myMap.data.FIRST .. myMap.data.LAST LOOP
    DBMS_OUTPUT.PUT_LINE(myMap.data(i).fst || ' ' || myMap.data(i).snd);
  END LOOP;
END;
*/