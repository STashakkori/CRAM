<?php
	include_once('includes/account.php');

	if(isLoggedIn())
		echo getUsername();
	else
		echo "not logged in!";

?>