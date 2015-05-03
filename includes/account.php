<?php
	include_once('database.php');
	session_name("CRAM");
	session_start();
	
	function login($username,$password,$remember) {
		
		$db=MySqlDatabase::getInstance();
		
		$username = $db->secure_string($username);
		$password = $db->secure_string($password);
		
		$password = hash( "sha512" , $password );
		
		
		
		$validLogin=$db->fetchOneRow("SELECT * FROM cram_account WHERE acct_username='$username' AND acct_password='$password'");
		
		if($validLogin) {
			//setcookie( "login",  );
			$_SESSION['logged_in'] = true;
			$_SESSION['username'] = $username;
			$_SESSION['firstname'] = $validLogin->acct_first_name;
			$_SESSION['lastname'] = $validLogin->acct_last_name;
			$_SESSION['account_type'] = $validLogin->acct_type;
			$_SESSION['department'] = $validLogin->dpt_prefix;
			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['login_time']=time();
			if($remember)
				session_set_cookie_params(2*7*24*60*60);
			//session_start();
			return true;
		} 
		return false;
	}
	
	function logout() {
		session_unset();
	}
	
	function isLoggedIn() {
		if(!isset($_SESSION['logged_in']) or !isset($_SESSION['ip']))
			return false;
		return $_SESSION['logged_in'] && $_SESSION['ip']==$_SERVER['REMOTE_ADDR'];
	}
	
	function getDepartment() {
		return $_SESSION['department'];
	}
	function getUsertype() {
		return $_SESSION['account_type'];
	}
	function getUsername() {
		return $_SESSION['username'];
	}
	function getFullname() {
		return $_SESSION['firstname']." ".$_SESSION['lastname'];
	}
?>