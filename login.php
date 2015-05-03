<?php

	include_once('includes/database.php');
	include_once('includes/account.php');

	$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : "";
	$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
	$remember = isset($_REQUEST['remember']) ? true : false;
	
	if(login($username,$password,$remember))
		echo "success!";
	else
		echo "failure!";
	
	
	
	
	
	

		
?>