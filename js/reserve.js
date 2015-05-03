var numDaysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31]
var storedRooms = null;
var monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
$(document).ready(function() {
	
		$("input[value='Submit']").button();
		$( "#logoutbtn" ).button();
		$( "#dashboard" ).button();
	
	makeServiceCall("getBuildings","",function(buildings)
	{
		for(var i=0;i<buildings.length;i++)
			$('#Buildingbox').append("<option value='"+buildings[i]+"'>"+buildings[i]+"</option>");
	
	
		buildingChanged();
	});

	$('#Buildingbox').change(function() { buildingChanged(); });
	$('#RoomBox').change(function() {roomChanged();});
	
	var now = new Date();
	var nowYear = now.getFullYear();
	var nowMonth = now.getMonth();
	var nowDay = now.getDate();
	

	
	$('#dateyear').change(function() {
	
		setMonths();
		setDays();
	});
	$('#datemonth').change(function() {
	
		setDays();
	});	
	setYears();
	setMonths();
	setDays();
	
	setStartTime();
	setEndTime();
	$('#Submit').click(function(ev) {
		submitReservation();
		ev.stopPropagation();
		ev.preventDefault();
	});
	
	
	$('#logoutbtn').click(function(ev) {
		makeServiceCall("logout",'',function(result)
		{
			location.href="index.php";
		
		});
	});
	
	$('#dashboard').click(function(ev) {
		location.href="dashboard.php";
	});
	
});
function setYears()
{
	$('#dateyear').empty();
	var now = new Date();
	var nowYear = now.getFullYear();
	$('#dateyear').append("<option value='"+nowYear+"'>"+nowYear+"</option>");
	$('#dateyear').append("<option value='"+(nowYear+1)+"'>"+(nowYear+1)+"</option>");
	
}
function setMonths()
{
	$('#datemonth').empty();
	var now = new Date();
	var nowYear = now.getFullYear();
	var nowMonth = now.getMonth();
	var selectedYear = $('#dateyear').val();
	var start = 0;
	if (selectedYear==nowYear)
		start = nowMonth;
		
	while (start<12)
	{
		$('#datemonth').append("<option value='"+start+"'>"+monthNames[start]+"</option>");
		start++;
	}
	

}
function setDays()
{
	$('#dateday').empty();
	var now = new Date();
	var nowYear = now.getFullYear();
	var nowMonth = now.getMonth();
	var nowDay = now.getDate();
	var selectedYear = $('#dateyear').val();
	var selectedMonth = $('#datemonth').val();
	var start = 0;
	if (selectedYear==nowYear && selectedMonth==nowMonth)
		start = nowDay-1;
		
	var maxDays = numDaysInMonth[selectedMonth];
	while (start<maxDays)
	{
		$('#dateday').append("<option value='"+start+"'>"+(start+1)+"</option>");
		start++;
	}
	

}
function setStartTime()
{
	$('#starthours').append("<option value='0'>12</option>");
	for (var hrs = 1;hrs<=11;hrs++)
	{
		$('#starthours').append("<option value='"+hrs+"'>"+hrs+"</option>");
	}
	for (var mins = 0;mins<60;mins+=15)
	{
		var dispmin = mins;
		if (dispmin<10) dispmin = mins + "0";
		$('#startminutes').append("<option value='"+mins+"'>"+dispmin+"</option>");
	}
}
function setEndTime()
{
	$('#endhours').append("<option value='0'>12</option>");
	for (var hrs = 1;hrs<=11;hrs++)
	{
		$('#endhours').append("<option value='"+hrs+"'>"+hrs+"</option>");
	}
	for (var mins = 0;mins<60;mins+=15)
	{
		var dispmin = mins;
		if (dispmin<10) dispmin = mins + "0";
		$('#endminutes').append("<option value='"+mins+"'>"+dispmin+"</option>");
	}

}
function buildingChanged()
{

	var bldg_name = $('#Buildingbox').val();
	var obj = {};
	obj["bldg_name"] = bldg_name;
	$('#RoomBox').empty();
	makeServiceCall("getRooms",obj,function(rooms)
	{
		storedRooms = rooms;
		for(var i=0;i<rooms.length;i++)
			$('#RoomBox').append("<option value='"+rooms[i]["room"]["room_number"]+"'>"+rooms[i]["room"]["room_number"]+"</option>");
			
		roomChanged();
	});
}
function roomChanged()
{
	$('#roomdata').empty();
	var room_num = $('#RoomBox').val();
	var workingRoom = null;
	for(var i=0;i<storedRooms.length;i++)
	{
		if (storedRooms[i]["room"]["room_number"] == room_num)
		{
			workingRoom = storedRooms[i];
			break;
		}
	}
	if (workingRoom == null) return;
	console.log(workingRoom);
	var element = "Number of Seats: "+workingRoom["room"]["room_seats"]
	+"<br />"
	+"Department: "+workingRoom["room"]["dpt_prefix"]
	+"<br />"
	+"Is Lab: "+(workingRoom["room"]["room_lab"] == 1 ? "Yes" : "No")
	+"<br />"
	+"Handicap Access: "+(workingRoom["room"]["room_handicap"] == 1 ? "Yes" : "No")
	+"<br /><br />"
	+"List of Equipment<br />";
	
	if (workingRoom["equipment"].length == 0)
		element += "None";
		
	else
	{
		for (var i=0;i<workingRoom["equipment"].length;i++)
		{
			element += workingRoom["equipment"][i]["eqpt_name"] + " => "+workingRoom["equipment"][i]["eqpt_quantity"]+"<br />";
		}
	}
	$('#roomdata').append(element);
}

function submitReservation()
{

	var bldg_name = $('#Buildingbox').val();
	var room_num = $('#RoomBox').val();
    var dateday = Number($('#dateday').val());
    var datemonth =$('#datemonth').val();
    var dateyear = $('#dateyear').val();
    var starthours = Number($('#starthours').val());
    var startminutes = $('#startminutes').val();
    var startampm = $('#startampm').val();
    var endhours = Number($('#endhours').val());
    var endminutes = $('#endminutes').val();
    var endampm = $('#endampm').val();
	var groupsize = $('#groupsize').val();
	var reason = $('#reason').val();



	 
    if(startampm=="PM")
		starthours += 12;
		
    var resv_start = new Date(dateyear,datemonth , dateday+1, starthours, startminutes);


    if(endampm=="PM")
		endhours += 12
    
	var resv_end = new Date(dateyear,datemonth, dateday+1, endhours, endminutes);
   
   
    // console.log("day "+dateday);
	// console.log("month "+datemonth);
	// console.log("year "+dateyear);
	// console.log("starthour "+starthours);
	// console.log("startminute "+startminutes);
	// console.log("endhours "+endhours);
	// console.log("endminutes "+endminutes);

	 
   
	// alert(resv_start)
	// alert(resv_end);
   
   	var obj = {};
	obj["bldg_name"] = bldg_name
	obj["room_num"] = room_num
    obj["resv_start"]=resv_start.getTime()/1000;
    obj["resv_end"] = resv_end.getTime()/1000;
	obj["groupsize"] =groupsize;
	obj["reason"] = reason;

	makeServiceCall("reserveRoom",obj,function(result)
	{
		if (result == true)
		{
			alert("You have successfully reserved a room!");
			location.href = "dashboard.php";
		}
		else if(result == false)
		{
			alert("Something went wrong!");
			//location.href = "dashboard.php";
		}
		else
		{
			alert(result);
		}
	
	});
	
	

}
