<?
	include_once('includes/database.php');
	include_once('includes/account.php');
	if (!isLoggedIn())
		header("Location: index.php");
?>
<!DOCTYPE html>
<html>
	<head> 
	<link rel="stylesheet" type ="text/css" href ="Reserve.css">
	<link href="css/sunny/jquery-ui-1.10.2.custom.css" rel="stylesheet">
	<!-- Shortcut icon -->
  	<link rel="shortcut icon" href="favicon.ico">
	
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.js"></script>
	<script src="js/json2.js" type="text/javascript"></script>
	<script src="js/services.js" type="text/javascript"></script>
	<script src="js/reserve.js" type="text/javascript"></script>
		<title> CRAM Reservation Page </title>
	</head>
	<body>
		<button id='logoutbtn'>Logout</button>
		<button id='dashboard'>Dashboard</button>
		<div class="container">
		<? echo "<h2 id='greeting'>Welcome ".getFullname()."</h2>"; ?>
		<form class ="form-reserve">
		<h2 class="form-reserve-heading"> Reserve A Class Room</h2>
		 <p  />
		<b>Building <select name = "Building" id="Buildingbox" class='stitched'></select><br />

		Room
		<select name = "Room" id="RoomBox" class='stitched'></select><br />

		Date <select id="dateday" name="dateday" class='stitched'></select>
		<select id="datemonth" name="datemonth" class='stitched'></select>
		<select id="dateyear" name="dateyear" class='stitched'></select><br />
		Start Time
		<select id="starthours" name="starthours" class='stitched'></select>
		<select id="startminutes" name="startminutes" class='stitched'></select> 
		<select id = "startampm" name="startampm" class='stitched'> 
			<option value = "AM"> AM </option>
			<option value = "PM">PM </option>
		</select>
		<br />
		End Time
		<select  id="endhours" name="endhours" class='stitched'></select>
		<select id="endminutes" name="endminutes" class='stitched'></select> 
		<select  id="endampm" name="endampm" class='stitched'> 
			<option value = "AM"> AM </option>
			<option value = "PM">PM </option>
		</select> 
		<br />
		Group Size?
		<input type="textbox" id="groupsize" class='stitched' value='1'/><br />
		Reason for Reservation?
		<textarea id="reason" class='stitched'></textarea><br />
		<input type="button" value="Submit" name = "Submit" id="Submit"></input>
		</b>
		</form>
   		</div> <!-- /container -->
	</body>
</html>
