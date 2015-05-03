$(document).ready(function() {

	/*
	$('#loginsubmit').click(function(ev) {
		login();
	});
	*/
});

function login()
{
	var uname = $('#lgnuser').val();
	var upass = $('#lgnpass').val();

	var obj = {}
	obj["username"] = uname;
	obj["password"] = upass;
	obj["remember"] =  $('#remember').is(':checked');
	
	makeServiceCall("login",obj,function(result) {
	
	
		if (result == false)
			alert("Login failure!");
		else if (result == true)
		{
			location.href = "dashboard.php";
		}
	});
	
	

} 