<?php
	include_once('database/MySQLDatabase.php');
	include_once('database/MySQLResultSet.php');
	$db = MySqlDatabase::getInstance();
	try {
		$db->connect('localhost', 'hernandeznp', '900502281', '3430-s13-t5');
	} 
	catch (Exception $e) {
		die($e->getMessage());
	}
	
	$db = new mysqli('localhost', 'hernandeznp', '900502281', '3430-s13-t5');
	if ($db->connect_error) {
		die('Connect Error (' . $db->connect_errno . ') '
				. $db->connect_error);
	}
?>