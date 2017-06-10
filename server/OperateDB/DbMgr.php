<?php
class DBMgr{
	public $mysql_Host	=	'', $Port	=	'', $userName	=	'', $passWord	=	'', $DatabaseName	=	'', $connection;
	
	//Constructor function to connect and select database.
	public function DBMgr()
	{
		require_once __DIR__.'/DbConfig.php';
		$this->mysql_Host	=	$host;
		$this->Port			=	$port;
		$this->userName		=	$username;
		$this->passWord		=	$password;
		$this->DatabaseName	=	$database;
		
		$this->connect_DB();		
	}
	
	public function connect_SpecificDatabase($mysql_Host, $userName, $passWord, $DatabaseName, $Port){
		$db	= new mysqli($mysql_Host, $userName, $passWord, $DatabaseName, $Port);
		return $db;
	}
	
	private function connect_DB()
	{
		if(!isset($this->connection))
		{
			$db	= new mysqli('p:'.$this->mysql_Host, $this->userName, $this->passWord, $this->DatabaseName, $this->Port);
			$this->connection	=	$db;
		}
		else
			$db	=	$this->connection;

		if (!$db)
			return "Error: Connection failed '".$this->set_mysqlError()."'";	
	}
	
	private function set_mysqlError($Query, &$DB_OperationError = '')
	{
		include_once __DIR__.'./../ErrorHandling.php';
		$DB_OperationError	=	"";
		$DB_OperationError	=	mysqli_error($this->connection);
		ErrorLogging('query: --'.$Query.' -- '.'Error: --'.$DB_OperationError);
	}
	
	private function FieldValuePair_ToString($FieldValueArray)
	{
		$FieldsAsString	=	"";
		foreach ($FieldValueArray as $FieldName => $value) {
			if($FieldsAsString != "")
				$FieldsAsString	.=	', ';
			
/*			if(strpos($value, '+') > 0 || strpos($value, '-') > 0)	
				$FieldsAsString	.=	 $FieldName." = ".$value;
*/
			if(strpos($value,'\'') !== false) {
				$value=	addslashes($value);
			}
			if(strpos($value,'\"') !== false) {
				$value=	addslashes($value);
			}

			if($value	!= 'now()')	
				$FieldsAsString	.=	 $FieldName." = '".$value."'";
			else
				$FieldsAsString	.=	 $FieldName." = ".$value;
		}
		return $FieldsAsString;
	}
	
	public function Prepare_Output($output, $output_Format, $keyField_Output = '')
	{
		if($output->num_rows == 0)
			return 0;	
			
		switch($output_Format)
		{
			case 'RESULT':
				return $output;
			break;
			case 'NUM_ROWS':
				return $output->num_rows;
			break;
			case 'NUM_ARR':
			    while ($row = $output->fetch_array(MYSQLI_NUM)) {
					$output_arr[]	=	$row;
				}
				return $output_arr;
			break;
			case 'ASSOC':
			default:
			    while ($row = $output->fetch_assoc()) {
					if($keyField_Output != '' && isset($row[$keyField_Output])){
						$output_arr[strval($row[$keyField_Output])]	=	$row;
					}
					else
						$output_arr[]	=	$row;
				}
				return $output_arr;
			break;
		}
	}
	
	private function Prepare_Query($input_array, $prepare_For)
	{
		$Query	=	"";
		switch($prepare_For)
		{
			case 'READ':
				if($input_array['Fields'] == "" || $input_array['Fields'] == NULL || $input_array['Table']	==	"" || $input_array['Table']	==	NULL)
					return false;

					$Query	.=	"SELECT ";
					$Query	.=	$input_array['Fields'];
					$Query	.=	" FROM ";
					$Query	.=	$input_array['Table'];
					
				if(isset($input_array['clause']) && $input_array['clause'] != "" && $input_array['clause'] !== NULL)
				{
					$Query	.=	" WHERE ";
					$Query	.=	$input_array['clause'];
				}
					
				if(isset($input_array['order']) && $input_array['order'] != "" && $input_array['order'] !== NULL)
				{
					$Query	.=	" ORDER BY ";
					$Query	.=	$input_array['order'];
				}
			break;
			case 'INSERT':
				if($input_array['Fields'] == "" || $input_array['Fields'] == NULL || $input_array['Table']	==	"" || $input_array['Table']	==	NULL)
					return false;

					$Query	.=	"INSERT INTO ";
					$Query	.=	$input_array['Table'];
					$Query	.=	" SET ";
					$Query	.=	$this->FieldValuePair_ToString($input_array['Fields']);
			break;
			case 'UPDATE':
				if($input_array['Fields'] == "" || $input_array['Fields'] == NULL || $input_array['Table']	==	"" || $input_array['Table']	==	NULL)
					return false;

					$Query	.=	"UPDATE ";
					$Query	.=	$input_array['Table'];
					$Query	.=	" SET ";
					$Query	.=	$this->FieldValuePair_ToString($input_array['Fields']);
					
				if(isset($input_array['clause']) && $input_array['clause'] != "" && $input_array['clause'] !== NULL)
				{
					$Query	.=	" WHERE ";
					$Query	.=	$input_array['clause'];
				}
			break;
			case 'DELETE':
				if($input_array['Table']	==	"" || $input_array['Table']	==	NULL)
					return false;

					$Query	.=	"DELETE FROM ";
					$Query	.=	$input_array['Table']."";
					
				if(isset($input_array['clause']) && $input_array['clause'] != "" && $input_array['clause'] !== NULL)
				{
					$Query	.=	" WHERE ";
					$Query	.=	$input_array['clause'];
				}
			break;
		}
		
		return $Query;
	}
	
	public function Read($input_array, $outputFormat="ASSOC", $DataType = "", $keyField_Output = '')
	{
		$Query	=	$this->Prepare_Query($input_array, 'READ');
		$output	=	"";
		if(!$Query)
			$output=	'INVALID';
		else
		{	
			$result 	= $this->connection->query($Query);
			if (!$result)
			{
				$this->set_mysqlError($Query);
				$output	=	 false;	
			}
			else
			{
				$output	=	$this->Prepare_Output($result, $outputFormat, $keyField_Output);	
				if($DataType != "")
					$output	=	json_encode($output);	
			}
		}
		return $output;
	}
	
	public function Insert($input_array)
	{
		$Query	=	$this->Prepare_Query($input_array, 'INSERT');
		$output	=	"";
		if(!$Query)
			$output=	'INVALID';
		else
		{	
			$result 	= $this->connection->query($Query);
			if (!$result)
			{
				$this->set_mysqlError($Query);
				$output	=	 false;	
			}
			else
			{
				$output	=	mysqli_insert_id($this->connection);	
				if(!$output)
					return true;
			}
		}
		return $output;
	}
	
	public function Update($input_array)
	{
		$Query	=	$this->Prepare_Query($input_array, 'UPDATE');
		$output	=	"";
		if(!$Query)
			$output=	'INVALID';
		else
		{
			$result 	= $this->connection->query($Query);
			if (!$result)
			{
				$this->set_mysqlError($Query);
				$output	=	 false;	
			}
			else
			{
				$output	=	true;	
			}
		}
		return $output;
	}
	
	public function Delete($input_array)
	{
		$Query	=	$this->Prepare_Query($input_array, 'DELETE');
		$output	=	"";
		if(!$Query)
			$output=	'INVALID';
		else
		{	
			$result 	= $this->connection->query($Query);
			if (!$result)
			{
				$this->set_mysqlError($Query);
				$output	=	 false;	
			}	
			else
			{
				$output	=	true;	
			}
		}
		return $output;
	}
	
	public function Query($Query, $outputFormat = 'ASSOC', $DataType = "", $keyField_Output = '')
	{
		$result 	= $this->connection->query($Query);
		if (!$result)
		{
			$this->set_mysqlError($Query);
			$output	=	 false;	
		}	
		else
		{
			$output	=	$this->Prepare_Output($result, $outputFormat, $keyField_Output);	
			if($DataType != "")
				$output	=	json_encode($output);	
		}
		return $output;
	}
};
$perform_Database_Operation	=	new DBMgr();
$DB_OperationError	=	"";
?>