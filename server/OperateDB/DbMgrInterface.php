<?php
/*
 * Author: Aditya 
 * date: 31-jul-2014
 * Description: This module implments all database operations functionalities. 
 * Passing the query variables in defined format one can retireve/insert/update/delete the data from/into database. 
 * 
 * 
 */

//Includes complete implementation of DB operations
require_once	__DIR__.'/DbMgr.php';

/*=====================================================READ==============================================================*/
/*
* @access public
* @param  associative_Array $readInput. Format is described below:
*			array(
*				'Fields'=> 'Field1, Field2, Field3',		//Mandatory
*				'Table'=> 'TableName',						//Mandatory
*				'clause'=> 'FieldName = FieldValue',		//optional
*				'order'	=> 'FieldName DESC'					//optional		
*			)
			clause refers exact/valid values as mysql query accepts.
			clause is a condition to filter output. e.g. 'FieldName = DesiredValue' would return entries where FieldName has DesiredValue value only.
			Order refers exact/valid values as mysql query accepts and is used to sort data selection. example value can be 'FieldName ASC' or 'FieldName DESC'.
* @param  string $outputFormat. Values can be one of  'RESULT, NUM_ROWS, NUM_ARR, ASSOC', where ASSOC is default value.
*		  It defines whether the read should return 'mysql result resource/ Numbers of rows in result set / Numbered array / Associative array
* @param  string $DataType.	Value can only be 'AS_JSON' else ''. Use this to get data set returned as json.
*
* @return false, else 0(zero- for no corresponding entry), else output in described format. If mysql error is to be accessed, it is available with a aglobal variable $DB_OperationError

*/
function DB_Read($readInput, $outputFormat	=	"ASSOC", $DataType	=	"", $keyField_Output = '')
{
	global	$perform_Database_Operation;
	return	$perform_Database_Operation->Read($readInput, $outputFormat, $DataType, $keyField_Output);
}

/*=====================================================INSERT==============================================================*/
/*
* @access public
* @param  associative_Array $insertInput. Format is described below:
*			array(
*				'Table'=> 'TableName',						//Mandatory
*				'Fields'=> array(							//Mandatory
					'FieldName1' =>Value1,
					'FieldName2' =>Value2,
					'FieldName_n'=>Value_n
				)	
*			)
*			So in above associative array the element refered by key 'Fields' is itself an associative array which would specify DbField and corresponding Value to be stored
* @return  Inserted Id on success, else false on failure. If mysql error is to be accessed, it is available with a aglobal variable $DB_OperationError
*/
function DB_Insert($insertInput)
{
	global	$perform_Database_Operation;
	return	$perform_Database_Operation->Insert($insertInput);
}

/*=====================================================UPDATE==============================================================*/
/*
* @access public
* @param  associative_Array $updateInput. Format is described below:
*			array(
*				'Table'=> 'TableName',						//Mandatory
*				'Fields'=> array(							//Mandatory
					'FieldName1' =>Value1,
					'FieldName2' =>Value2,
					'FieldName_n'=>Value_n
				),	
*				'clause'=> 'FieldName = FieldValue',		//optional
*			)
*			So in above associative array the element refered by key 'Fields' is itself an associative array which would specify DbField and corresponding Value to be stored
* @return  true on success, else false. If mysql error is to be accessed, it is available with a aglobal variable $DB_OperationError
*/
function DB_Update($updateInput)
{
	global	$perform_Database_Operation;
	return	$perform_Database_Operation->Update($updateInput);
}

/*=====================================================DELETE==============================================================*/
/*
* @access public
* @param  associative_Array $deleteInput. Format is described below:
*			array(
*				'Table'=> 'TableName',						//Mandatory
*				'clause'=> 'FieldName = FieldValue',		//OPTIONAL. But if not specified all the data from database would be deleted
*			)
*			So in above associative array the element refered by key 'Fields' is itself an associative array which would specify DbField and corresponding Value to be stored
* @return  true on success, else false on failure. If mysql error is to be accessed, it is available with a aglobal variable $DB_OperationError
*/
function DB_Delete($deleteInput)
{
	global	$perform_Database_Operation;
	return	$perform_Database_Operation->Delete($deleteInput);
}

/*=====================================================RUN ABSOLUTE QUERY==============================================================*/
/*
* @access public
* @param  Query as a string. Query can be of any type
* @return  result resource on success, else false on failure. If mysql error is to be accessed, it is available with a aglobal variable $DB_OperationError
*/
function DB_Query($query, $outputFormat	=	"ASSOC", $DataType	=	"", $keyField_Output = '')
{
	global	$perform_Database_Operation;
	return	$perform_Database_Operation->Query($query, $outputFormat, $DataType, $keyField_Output);
}

/*=====================================================Close mysql connection or destroy mysqli object==============================================================*/
/*
* @access public
*/
function DB_Close()
{
	global	$perform_Database_Operation;
	unset($perform_Database_Operation);
	return;
}
?>