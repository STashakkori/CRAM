<?php

	include_once('includes/account.php');
	if (!isLoggedIn())
		header("Location: index.php");


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CRAM Dashboard</title>
	<meta name="author" content="Nathan Hernandez, Jennifer Lindley, Chris Hegre, Sina Tashakkori">
    <meta name="description" content="The Login Page to the CRAM Room Reservation System">
	<link rel="stylesheet" type="text/css" href="index.css">
	<link href="css/sunny/jquery-ui-1.10.2.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.js"></script>
	<script src="js/cleanDate.js"></script>
	<script src="js/json2.js" type="text/javascript"></script>
	<script src="js/services.js" type="text/javascript"></script> 
	<script src="js/dashboard.js" type="text/javascript"></script>
 
	<!-- Shortcut icon -->
  <!--<link rel="shortcut icon" href="favicon.ico" method='get'>-->
  </head> 

  <body>
	<button id='logoutbtn'>Logout</button>
	<? echo "<h2 id='greeting'>Welcome ".getFullname()."</h2>"; ?>
	<button id='resv_room'>Reserve a Room</button>
    <div class="container" id='resv_list'>
	
     

    </div> <!-- /container -->

  </body>
</html>
