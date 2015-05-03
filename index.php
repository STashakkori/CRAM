<?
	include_once('includes/database.php');
	include_once('includes/account.php');
	if (isLoggedIn())
		header("Location: dashboard.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CRAM Login Page</title>
	<meta name="author" content="Nathan Hernandez, Jennifer Lindley, Chris Hegre, Sina Tashakkori">
    <meta name="description" content="The Login Page to the CRAM Room Reservation System">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="js/json2.js" type="text/javascript"></script>
	<script src="js/services.js" type="text/javascript"></script> 
	<script src="js/login.js" type="text/javascript"></script>
 
	<!-- Shortcut icon -->
  <!--<link rel="shortcut icon" href="favicon.ico" method='get'>-->
  </head> 

  <body>

    <div class="container">

      <form class="form-signin" onsubmit="login(); return false;"> 
        <h2 class="form-signin-heading">CRAM Login Page</h2> 
		</br>
        <input type="text" class="input-block-level stitched"  placeholder="Appalnet Email Address" id='lgnuser' name="username"><br />
        <input type="password" class="input-block-level stitched" placeholder="Appalnet Password" id='lgnpass' name="password">
		</br>
		</br>
        </label>
        <button class="btn btn-large btn-primary" id="loginsubmit">Begin Session</button>
		</br>
		</br>
		<label class="checkbox">
          <input type="checkbox" value="remember-me" id='remember' name="remember" > Remember me 
      </form>

    </div> <!-- /container -->

  </body>
</html>
