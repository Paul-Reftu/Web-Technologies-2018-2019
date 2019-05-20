/*
DROP TYPE MATRIX;
/
DROP TYPE ARRAY_2D;
/
DROP TYPE ARRAY_1D;
/
*/

/*
 * one-dimensional array type
 */
CREATE OR REPLACE TYPE Array_1D IS TABLE OF FLOAT;
/
/*
 * two-dimensional array type
 */
CREATE OR REPLACE TYPE Array_2D IS TABLE OF ARRAY_1D;
/

CREATE OR REPLACE TYPE Matrix AS OBJECT
(
  noOfRows INTEGER,
  noOfCols INTEGER,
  data ARRAY_2D,
  
  CONSTRUCTOR FUNCTION Matrix RETURN SELF AS RESULT,
  
  CONSTRUCTOR FUNCTION Matrix(noOfRows INTEGER, noOfCols INTEGER)
    RETURN SELF AS RESULT,
    
  MEMBER PROCEDURE randomizeMatrix(minBound FLOAT, maxBound FLOAT),
    
  MEMBER FUNCTION getNoOfRows RETURN INTEGER,
  MEMBER FUNCTION getNoOfCols RETURN INTEGER,
  MEMBER FUNCTION getData RETURN ARRAY_2D,
  
  MEMBER PROCEDURE setNoOfRows(noOfRows INTEGER),
  MEMBER PROCEDURE setNoOfCols(noOfCols INTEGER),
  MEMBER PROCEDURE setData(data ARRAY_2D),
  
  MEMBER PROCEDURE setDataCell(i INTEGER, j INTEGER, val FLOAT),
  
  STATIC FUNCTION manipulate(m1 MATRIX, m2 MATRIX, operation VARCHAR) RETURN MATRIX,
  STATIC FUNCTION manipulate(m MATRIX, x FLOAT, operation VARCHAR) RETURN MATRIX,
  STATIC FUNCTION manipulate(x FLOAT, m MATRIX, operation VARCHAR) RETURN MATRIX,
  
  MEMBER PROCEDURE manipulate(m MATRIX, operation VARCHAR),
  MEMBER PROCEDURE manipulate(x FLOAT, operation VARCHAR),
  
  
  STATIC FUNCTION transpose(m MATRIX) RETURN MATRIX,
  
  MEMBER PROCEDURE transpose,
  
  STATIC FUNCTION colVectorSoftmax(m MATRIX) RETURN MATRIX,
  
  STATIC FUNCTION exp(m MATRIX) RETURN MATRIX,
  
  STATIC FUNCTION elemSum(m MATRIX) RETURN FLOAT,
  
  MEMBER PROCEDURE appendColVector(vector ARRAY_1D),
  
  STATIC FUNCTION colVectorToMatrix(v ARRAY_1D) RETURN MATRIX,
  
  MEMBER PROCEDURE Print
);
/


/*******************************************************************/
/*************************** MATRIX BODY ***************************/
/*******************************************************************/
CREATE OR REPLACE TYPE BODY Matrix AS
  /**
   * constructs an object of type 'Matrix'
   * @return the matrix object w/ no rows and columns
   */
  CONSTRUCTOR FUNCTION Matrix RETURN SELF AS RESULT AS
  BEGIN
    SELF.noOfRows := 0;
    SELF.noOfCols := 0;
    SELF.data := ARRAY_2D();
    
    RETURN;
  END Matrix;

  /**
   * @param noOfRows the number of rows that the matrix should have
   * @param noOfCols the number of columns that the matrix should have
   * initialize matrix dimensions w/ given parameters
   */
  CONSTRUCTOR FUNCTION Matrix(noOfRows INTEGER, noOfCols INTEGER) 
    RETURN SELF AS RESULT AS
  BEGIN
    SELF.noOfRows := noOfRows;
    SELF.noOfCols := noOfCols;
    SELF.data := ARRAY_2D();
    SELF.data.EXTEND(noOfRows);
    
    FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
      SELF.data(i) := ARRAY_1D();
      SELF.data(i).EXTEND(noOfCols);
    END LOOP;
    
    RETURN;
  END Matrix;
  
  /*
   * randomize the current object's data member
   * @param minBound the lower bound of the interval of random numbers to be generated
   * @param maxBound the upper bound of the interval of random numbers to be generated
   */
  MEMBER PROCEDURE randomizeMatrix (minBound FLOAT, maxBound FLOAT) AS
  BEGIN
    FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
      FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
        SELECT DBMS_RANDOM.VALUE(minBound, maxBound) INTO SELF.data(i)(j) FROM DUAL;
      END LOOP;
    END LOOP;
  END;
  
  /*
   * @return the noOfRows
   */
  MEMBER FUNCTION getNoOfRows RETURN INTEGER AS
  BEGIN
    RETURN SELF.noOfRows;
  END getNoOfRows;
  
  /**
   * @return the noOfCols
   */
  MEMBER FUNCTION getNoOfCols RETURN INTEGER AS
  BEGIN
    RETURN SELF.noOfCols;
  END getNoOfCols;
  
  /**
   * @return the data
   */
  MEMBER FUNCTION getData RETURN ARRAY_2D AS
  BEGIN
    RETURN SELF.data;
  END getData;
  
  /**
   * @param noOfRows the number of rows to set
   */
  MEMBER PROCEDURE setNoOfRows(noOfRows INTEGER) AS
  BEGIN
    SELF.noOfRows := noOfRows;
  END setNoOfRows;
  
  /**
   * @param noOfCols the number of cols to set
   */
  MEMBER PROCEDURE setNoOfCols(noOfCols INTEGER) AS
  BEGIN
    SELF.noOfCols := noOfCols;
  END setNoOfCols;
  
  /**
   * @param data the matrix data to set
   */
  MEMBER PROCEDURE setData(data ARRAY_2D) AS
  BEGIN
    SELF.data := data;
  END setData;
  
  /**
   * @param i the row number of the cell
   * @param j the col number of the cell
   * @param val the value to be put into the cell at (i, j)
   */
  MEMBER PROCEDURE setDataCell(i INTEGER, j INTEGER, val FLOAT) AS
  BEGIN
    SELF.data(i)(j) := val;
  END setDataCell;
  
  /*************************** BEGIN OF manipulate() methods ***************************/
  
  /**
   * @param m1 the first matrix
   * @param m2 the second matrix
   * @return the resulting matrix of the operation b/w the two given matrices
   */
  STATIC FUNCTION manipulate(m1 MATRIX, m2 MATRIX, operation VARCHAR) RETURN MATRIX AS
  BEGIN 
    IF (operation = 'add' OR operation = 'sub') AND 
    (m1.noOfRows <> m2.noOfRows OR m1.noOfCols <> m2.noOfCols) THEN
      RAISE_APPLICATION_ERROR(-20000, 'Matrix addition/subtraction undefined for two matrices of different dimensions.');
    ELSE
      DECLARE
      m MATRIX;
      BEGIN
        IF (operation = 'add') THEN
          BEGIN
            m := MATRIX(m1.noOfRows, m1.noOfCols);
            
            FOR i IN m.data.FIRST .. m.data.LAST LOOP
              FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
                m.setDataCell(i, j, m1.data(i)(j) + m2.data(i)(j));
              END LOOP;
            END LOOP; 
          END;
          
        ELSIF (operation = 'sub') THEN
          BEGIN
            m := MATRIX(m1.noOfRows, m1.noOfCols);
          
            FOR i IN m.data.FIRST .. m.data.LAST LOOP
              FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
                m.setDataCell(i, j, m1.data(i)(j) - m2.data(i)(j));
              END LOOP;
            END LOOP; 
          END;
          
        ELSIF (operation = 'mul') THEN
          IF (m1.noOfCols <> m2.noOfRows) THEN
            RAISE_APPLICATION_ERROR(-20001, 'Matrix dot product undefined for two matrices A and B where A.noOfCols != B.noOfRows.');
          END IF;
          
          BEGIN
            m := MATRIX(m1.noOfRows, m2.noOfCols);
          
            FOR i IN 1 .. m1.noOfRows LOOP
              FOR j IN 1 .. m2.noOfCols LOOP
                DECLARE
                    tempSum FLOAT := 0.0;
                BEGIN
                  FOR k IN 1 .. m1.noOfCols LOOP
                    tempSum := tempSum + m1.data(i)(k) * m2.data(k)(j);
                  END LOOP;
                  
                  m.setDataCell(i, j, tempSum);
                END;
              END LOOP;
            END LOOP; 
          END;
        END IF;
        
        RETURN m;
      END;
    END IF;
  END manipulate;
  
  /**
   * @param m a matrix
   * @param x the scalar value with which to perform the matrix scalar operation
   * @return the new matrix after scalar operation
   */
  STATIC FUNCTION manipulate(m MATRIX, x FLOAT, operation VARCHAR) RETURN MATRIX AS
  mPrime MATRIX := MATRIX(m.noOfRows, m.noOfCols);
  BEGIN
    IF (operation = 'add') THEN
      FOR i IN m.data.FIRST .. m.data.LAST LOOP
        FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
          mPrime.setDataCell(i, j, m.data(i)(j) + x);
        END LOOP;
      END LOOP;
      
    ELSIF (operation = 'sub') THEN
      FOR i IN m.data.FIRST .. m.data.LAST LOOP
        FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
          mPrime.setDataCell(i, j, m.data(i)(j) - x);
        END LOOP;
      END LOOP;
      
    ELSIF (operation = 'mul') THEN
      FOR i IN m.data.FIRST .. m.data.LAST LOOP
        FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
          mPrime.setDataCell(i, j, m.data(i)(j) * x);
        END LOOP;
      END LOOP;
    END IF;
    
    RETURN mPrime;
  END manipulate;
  
  /**
   * @param x the scalar value with which to perform the matrix scalar operation
   * @param m a matrix
   * @return the new matrix after scalar operation
   */
  STATIC FUNCTION manipulate(x FLOAT, m MATRIX, operation VARCHAR) RETURN MATRIX AS
  BEGIN
    RETURN Matrix.manipulate(m, x, operation);
  END manipulate;
  
  /**
   * @param m the matrix to perform the operation with
   */
  MEMBER PROCEDURE manipulate(m MATRIX, operation VARCHAR) AS
  BEGIN
    IF (SELF.noOfRows <> m.noOfRows OR SELF.noOfCols <> m.noOfCols) THEN
      RAISE_APPLICATION_ERROR(-20000, 'Matrix addition/subtraction/multiplication undefined for two matrices of different dimensions.');
    ELSE
      IF (operation = 'add') THEN    
        FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
          FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
            SELF.data(i)(j) := SELF.data(i)(j) + m.data(i)(j);
          END LOOP;
        END LOOP;
        
      ELSIF (operation = 'sub') THEN
        FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
          FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
            SELF.data(i)(j) := SELF.data(i)(j) - m.data(i)(j);
          END LOOP;
        END LOOP;
        
      ELSIF (operation = 'mul') THEN
          IF (SELF.noOfCols <> m.noOfRows) THEN
            RAISE_APPLICATION_ERROR(-20001, 'Matrix dot product undefined for two matrices A and B where A.noOfCols != B.noOfRows.');
          END IF;
          
          DECLARE
            mPrime MATRIX := MATRIX(SELF.noOfRows, m.noOfCols);
          BEGIN
            FOR i IN mPrime.data.FIRST .. mPrime.data.LAST LOOP
              FOR j IN mPrime.data(i).FIRST .. mPrime.data(i).LAST LOOP
                DECLARE
                    tempSum FLOAT := 0.0;
                BEGIN
                  FOR k IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
                    tempSum := tempSum + SELF.data(i)(k) * m.data(k)(j);
                  END LOOP;
                  
                  mPrime.setDataCell(i, j, tempSum);
                END;
              END LOOP;
            END LOOP; 
            
            SELF := mPrime;
          END;
        END IF;
      
    END IF;
  END manipulate;
  
  /**
   * @param x the scalar to perform the operation with
   */
  MEMBER PROCEDURE manipulate(x FLOAT, operation VARCHAR) AS
  BEGIN
    IF (operation = 'add') THEN
      FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
        FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
          SELF.data(i)(j) := SELF.data(i)(j) + x;  
        END LOOP;
      END LOOP;
      
    ELSIF (operation = 'sub') THEN
      FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
        FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
          SELF.data(i)(j) := SELF.data(i)(j) - x;  
        END LOOP;
      END LOOP;
      
    ELSIF (operation = 'mul') THEN
      FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
        FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
          SELF.data(i)(j) := SELF.data(i)(j) * x;  
        END LOOP;
      END LOOP;
    END IF;
  END manipulate;
  
  /*************************** END OF manipulate() methods ***************************/


  /*************************** BEGIN OF transpose() methods ***************************/

  /**
   * @param m the matrix to get the transpose of
   * @return the transposed version of the given matrix
   */
  STATIC FUNCTION transpose(m MATRIX) RETURN MATRIX AS
  mPrime MATRIX := MATRIX(m.noOfCols, m.noOfRows);
  BEGIN
    FOR i IN m.data.FIRST .. m.data.LAST LOOP
      FOR j IN m.data(i).FIRST .. m.data(i).LAST LOOP
        mPrime.setDataCell(j, i, m.data(i)(j));
      END LOOP;
    END LOOP;
    
    RETURN mPrime;
  END transpose;
  
  MEMBER PROCEDURE transpose AS
  BEGIN
    SELF.setData(Matrix.transpose(SELF).getData());
  END transpose;

  /*************************** END OF transpose() methods ***************************/  
  
  /**
   * applies the softmax function to a column vector
   * @param m the matrix representing the column vector
   * @return the new matrix representing the 'softmaxed' col. vector
   */
  STATIC FUNCTION colVectorSoftmax(m MATRIX) RETURN MATRIX AS
  colVector ARRAY_1D;
  newM MATRIX;
  BEGIN
    /*
     * exception treatment
     * if there are no rows (i.e the matrix is empty) 
     * or if the no. of columns is not 1 (and therefore the matrix is
     * not a column vector), then return null
     */
    IF (m IS NULL OR m.noOfRows = 0 OR m.noOfCols <> 1) THEN
      RETURN null;
    END IF;
    
    /*
     * get the 1-dimensional array equivalent of the col. vector
     */
    colVector := ARRAY_1D();
    colVector.EXTEND(m.data.COUNT);
    FOR i IN 1 .. m.data.COUNT LOOP
      colVector(i) := m.data(i)(1);
    END LOOP;
    
    /*
     * apply Math.softmax() to the array equiv. of the col. vector
     */
    colVector := Math.softmax(colVector);
    
    /*
     * get the matrix equivalent of the 1-dimensional array
     * (keeping it as a column vector)
     */
    newM := MATRIX(colVector.COUNT, 1);
    FOR i IN 1 .. colVector.COUNT LOOP
      newM.setDataCell(i, 1, colVector(i));
    END LOOP;
    
    RETURN newM;
  END colVectorSoftmax;
  
  /**
   * apply the exp() function on each element of the given matrix
   *    and return the resulting matrix
   * @param m the matrix to consider
   * @return the new matrix after applying exp() on each data cell
   */
  STATIC FUNCTION exp(m MATRIX) RETURN MATRIX AS
  mPrime MATRIX := MATRIX(m.noOfRows, m.noOfCols);
  BEGIN
    FOR i IN 1 .. m.noOfRows LOOP
      FOR j IN 1 .. m.noOfCols LOOP
        mPrime.setDataCell(i, j, Math.exp(m.data(i)(j)));
      END LOOP; 
    END LOOP;
    
    RETURN mPrime;
  END exp;
  
  /**
   * get the total sum of all data cells in the given matrix
   * @param m the matrix to consider
   * @return the sum of all matrix data cells
   */
  STATIC FUNCTION elemSum(m MATRIX) RETURN FLOAT AS
  elemSum FLOAT := 0.0;
  BEGIN
    FOR i IN 1 .. m.noOfRows LOOP
      FOR j IN 1 .. m.noOfCols LOOP
        elemSum := elemSum + m.data(i)(j);
      END LOOP; 
    END LOOP;
    
    RETURN elemSum;
  END elemSum;  
  
  /**
   * append a column vector to our matrix object
   * @param vector the col. vector to add
   */
  MEMBER PROCEDURE appendColVector(vector ARRAY_1D) AS
  BEGIN
    /*
     * if the given vector is null, then return w/o having done any operation
     */
    IF vector IS NULL THEN
      RETURN;
    END IF;
    
    /*
     * if our target matrix has no rows and/or columns, then
     * extend the no. of rows w/ the vector's size and the no.
     * of columns by 1
     */
    IF SELF.noOfRows = 0 AND SELF.noOfCols = 0 THEN
      SELF.noOfRows := vector.COUNT;
      SELF.noOfCols := 1;
      SELF.data.EXTEND(vector.COUNT);
      
      FOR i IN 1 .. vector.COUNT LOOP
        SELF.data(i) := ARRAY_1D();
        SELF.data(i).EXTEND(1);
        SELF.data(i)(1) := vector(i);
      END LOOP;
      
      RETURN;
    END IF;
    
    /*
     * if the vector's size is not equal to the target matrix's
     * no. of rows, then throw an exception
     */
    IF vector.COUNT <> SELF.noOfRows THEN
      RAISE_APPLICATION_ERROR(-20001, 'Size of given col. vector is not equal to the no. of rows of the target matrix.');
    END IF;
    
    SELF.noOfCols := SELF.noOfCols + 1;
    FOR i IN 1 .. SELF.data.COUNT LOOP
      SELF.data(i).EXTEND(1);
      SELF.data(i)(SELF.data(i).COUNT) := vector(i);
    END LOOP;
  END appendColVector;
  
  /**
   * create a matrix object from a column vector
   * @param v the column vector to consider
   * @return the matrix equivalent to the column vector passed as parameter
   */
  STATIC FUNCTION colVectorToMatrix(v ARRAY_1D) RETURN MATRIX AS
  m MATRIX;
  BEGIN
    m := MATRIX(v.COUNT, 1);
    
    FOR i IN 1 .. m.noOfRows LOOP
      m.setDataCell(i, 1, v(i));
    END LOOP;
    
    RETURN m;
  END colVectorToMatrix;
  
  /**
   * prints the contents of the matrix
   */
  MEMBER PROCEDURE Print AS
  BEGIN
    FOR i IN SELF.data.FIRST .. SELF.data.LAST LOOP
      FOR j IN SELF.data(i).FIRST .. SELF.data(i).LAST LOOP
        DBMS_OUTPUT.PUT(SELF.data(i)(j) || ' ');
      END LOOP;
      DBMS_OUTPUT.PUT_LINE('');
    END LOOP;
  END Print;
END;
/

/*
 * Usage example
 */
/*
SET SERVEROUTPUT ON;

DECLARE
  myMatrix MATRIX := MATRIX(4, 4);
  myNewMatrix MATRIX := MATRIX(4, 4);
  myMatrix2 MATRIX := MATRIX(6, 4);
  myNewMatrix2 MATRIX := MATRIX(4, 6);
  temp FLOAT := 0.0;
  
  myMatrix3 MATRIX := MATRIX(2, 2);
  myMatrix4 MATRIX := MATRIX(2, 2);
  
  colVecMatrix MATRIX := MATRIX(4, 1);
BEGIN
  FOR i IN myMatrix.data.FIRST .. myMatrix.data.LAST LOOP
    FOR j IN myMatrix.data(i).FIRST .. myMatrix.data(i).LAST LOOP
      myMatrix.setDataCell(i, j, 2.0);
    END LOOP;
  END LOOP;
  
  myNewMatrix := MATRIX.manipulate(myMatrix, 11.0, 'add');
  myNewMatrix := MATRIX.manipulate(myMatrix, myNewMatrix, 'add');
  myNewMatrix.manipulate(100.0, 'add');
  
  FOR i IN myMatrix2.data.FIRST .. myMatrix2.data.LAST LOOP
    FOR j IN myMatrix2.data(i).FIRST .. myMatrix2.data(i).LAST LOOP
      myMatrix2.setDataCell(i, j, temp);
    END LOOP;
    
    temp := temp + 1;
  END LOOP;
  
  myNewMatrix2 := Matrix.transpose(myMatrix2);
  
  myMatrix2.print();
  DBMS_OUTPUT.PUT_LINE('');
  myNewMatrix2.print();
  
  myMatrix3.setDataCell(1, 1, 1);
  myMatrix3.setDataCell(1, 2, 2);
  myMatrix3.setDataCell(2, 1, 3);
  myMatrix3.setDataCell(2, 2, 4);
  
  myMatrix4.setDataCell(1, 1, 11);
  myMatrix4.setDataCell(1, 2, 12);
  myMatrix4.setDataCell(2, 1, 13);
  myMatrix4.setDataCell(2, 2, 14);
  
  DBMS_OUTPUT.PUT_LINE('Matrix A: ');
  myMatrix3.print();
  
  DBMS_OUTPUT.PUT_LINE('Matrix B: ');
  myMatrix4.print();
  
  DBMS_OUTPUT.PUT_LINE('A . B: ');
  myMatrix3.manipulate(myMatrix4, 'mul');
  myMatrix3.print();
  
  DBMS_OUTPUT.PUT_LINE('Column vector matrix: ');
  FOR i IN 1 .. colVecMatrix.noOfRows LOOP
    colVecMatrix.data(i)(1) := i;
    DBMS_OUTPUT.PUT_LINE(colVecMatrix.data(i)(1));
  END LOOP;
  
  DBMS_OUTPUT.PUT_LINE('Softmaxed col. vector matrix: ');
  colVecMatrix := MATRIX.colVectorSoftmax(colVecMatrix);
  colVecMatrix.print();
END;
/
*/