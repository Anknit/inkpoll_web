<?php
require_once '../DbMgrInterface.php';
//Move this test to test file
if(isset($_GET['test'])) {
	if($_GET['test'] == 1)	//Read operation
	{
		$ReadTestArray	=	array(
			'Fields'=> 'Username, Password, UserType',
			'Table'=> 'userinfo',
			'clause'=> 'UserID IN(1,2)',
			'order'	=> 'UserID DESC'
		);
		$Result	=	DB_Read($ReadTestArray, 'ASSOC', 'AS_JSON');
	}
	if($_GET['test'] == 2)	//Insert operation
	{
		$ReadTestArray	=	array(
			'Table'=> 'userinfo',
			'Fields'=> array('Username'=> 'superadmin',
							 'Password'=> md5('superadmin'),
							 'UserType'=> 1
							 )
		);
		$Result	=	DB_Insert($ReadTestArray);
	}
	if($_GET['test'] == 3)	//Update operation
	{
		$ReadTestArray	=	array(
			'Table'=> 'userinfo',
			'Fields'=> array('City'=> 'Noida',
							 'RegisteredOn'=> 'now()'
							 ),
			'clause'=> 'UserID = 1 AND UserType = 1'
		);
		$Result	=	DB_Update($ReadTestArray);
	}
	if($_GET['test'] == 4)	//Delete operation
	{
		$ReadTestArray	=	array(
			'Table'=> 'userinfo',
			'clause'=> ' id = 1'
		);
		$Result	=	DB_>Delete($ReadTestArray);
	}
	echo var_dump($Result);
}
?>