var monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
$(document).ready(function() {
	
	$( "#logoutbtn" ).button();
	$( "#resv_room" ).button();
	
	makeServiceCall("getReservations","",function(result)
	{
	
		for (var i=0;i<result.length;i++)
		{
		
			addReservation(result[i]);
			$('.reservation_block').last().animate({opacity:1},Math.min(250*(i+1),1500));
			 
		}
	
	});
	
	$('#resv_room').click(function()
	{
		location.href="reserve.php";
	});
	
	$('#logoutbtn').click(function() {
		makeServiceCall( "logout", "" , function(){ location.href="index.php";} );
	});
});

function addReservation(resv)
{
	var reserverName = resv["acct_first_name"]+" "+resv["acct_last_name"];
	var bldg_name = resv["bldg_name"];
	var resv_id = resv["resv_id"];
	var room_number = resv["room_number"];
	var resv_start = resv["resv_start"];
	var resv_end = resv["resv_end"];
	var resv_size = resv["resv_size"];
	var reason = resv["resv_reason"];
	var dpt_prefix = resv["room_dept"];
	var acct_phone_number = resv["acct_phone_number"];
	var canDelete = resv["candelete"];
	
	var resv_start_date = new Date(resv_start*1000)
	var resv_end_date = new Date(resv_end*1000)
	var dispmin = resv_start_date.getMinutes();
	if (dispmin<10) dispmin = "0"+resv_start_date.getMinutes();
	//var dispStart = (resv_start_date.getDate()+1)+" "+monthNames[resv_start_date.getMonth()]+" "+resv_start_date.getFullYear()+" "+((resv_start_date.getHours()>12)?resv_start_date.getHours()-12:resv_start_date.getHours())+":"+dispmin+" "+((resv_start_date.getHours()>12)?"PM":"AM");
	dispStart = resv_start_date.format('mmmm, d hh:MM TT');
	
	
	var dispmin = resv_end_date.getMinutes();
	if (dispmin<10) dispmin = "0"+resv_end_date.getMinutes();
	//var dispEnd = (resv_end_date.getDate()+1)+" "+monthNames[resv_end_date.getMonth()]+" "+resv_end_date.getFullYear()+" "+((resv_end_date.getHours()>12)?resv_end_date.getHours()-12:resv_end_date.getHours())+":"+dispmin+" "+((resv_end_date.getHours()>12)?"PM":"AM");
	dispEnd = resv_end_date.format('mmmm, d hh:MM TT');
	
	var deleteString = "";
	if (canDelete)
	{
		deleteString = "<button class='delete' id='delRes"+resv_id+"'></button>";
	}
	
	
	
	var element = "<div class='reservation_block'>"
        +"<h2>Room Reservation</h2>" 
		+"<div style='text-align:left;'> <b>Building:<br/></b>"+bldg_name
		+"<br />"
		+"<b>Room #:<br/></b>"+room_number
		+"<br />"
		+"<b>Department:<br/></b>"+dpt_prefix
		+"<br />"
		+"<b>Reserved by:<br/></b>"+reserverName
		+"<br />"
		+"<b>Reservation Starts:<br/></b>"+dispStart
		+"<br />"
		+"<b>Reservation Starts:<br/></b>"+dispEnd
		+"<br />"
		+"<b>Reason:<br/></b>"+reason
		+"</div>"
		+deleteString
      +"</div>";
	$('#resv_list').append(element);
	
	
	$('#delRes'+resv_id).click(function() {
		deleteReservation(resv_id);
	
	})
}

function deleteReservation(resv_id)
{
	var obj = {}
	obj["resv_id"] = resv_id;
	makeServiceCall("deleteReservation",obj,function(result) {
	
		if (result == true)
		{
			$('#delRes'+resv_id).parent().remove();
			alert("Reservation Deleted!");
		}
		else
			alert("An error occured while deleting the reservation");
	
	});




}