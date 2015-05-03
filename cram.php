<?php
	include("includes/database.php");
	include("includes/account.php"); 

	/*************************************************** MAIN USER REQUEST TO DB CODE SECTION ***************************************************/
	/****************************************************************** Author:Chris Hegre *******************************************************************/
	/**************************************************************************************************************************************************************/
	if (!isset($_REQUEST["f"])) die('error no func');
	$function = $_REQUEST['f'];
	
	if (!isLoggedIn() && $function!='login')
		die('not logged in');
	
	
	if (!isset($_REQUEST["d"])) die('error no data');
	$data = json_decode($_REQUEST['d']);

	$validFunctions = array();
	
	$validFunctions["getRooms"] = function($data,$db)
	{
		$roomData = array();
		if (!isset($data->bldg_name)) die('getRooms error, expected bldg_name');
		$bldg_name = $db->real_escape_string($data->bldg_name);
		
		$query = "SELECT room_number,dpt_prefix,room_seats,room_handicap,room_lab FROM cram_room WHERE bldg_name='$bldg_name'";
		$result = $db->query($query);
		while ($row = $result->fetch_assoc()) {
			$arg =array();
			$arg["room"]=$row;
			
			$rnum = $row["room_number"];
			
			
			$query2 = "SELECT eqpt_name,eqpt_quantity FROM cram_equipment WHERE bldg_name='$bldg_name' and room_number=$rnum";
			$result2 = $db->query($query2);
			$equipArr = array();
			while ($row2 = $result2->fetch_assoc()) {
				$equipArr[] = $row2;
			}
			$arg["equipment"] = $equipArr;
			$roomData[] = $arg;
		}
		return json_encode($roomData);
	};
	$validFunctions["getBuildings"] = function($data,$db)
	{		
		$buildingData = array();
		$query = "SELECT DISTINCT bldg_name FROM cram_room";
		$result = $db->query($query);
		while ($row = $result->fetch_assoc()) {
			$buildingData[] = $row["bldg_name"];
		}
		return json_encode($buildingData); 
	};	
	/* END SECTION */

	 /************************************************************** LOGIN CODE SECTION ****************************************************************/
	/************************************************************* Author: Nathan Hernandez *************************************************************/
	/************************************************************************************************************************************************************/
	$validFunctions["login"] = function($data,$db)
	{
		if(isset($data->username) && isset($data->password))
			if(login($data->username,$data->password,isset($data->remember) ? true : false)) 
				return json_encode(true);
		return json_encode(false);
	};
	/* END SECTION */
	 
	/************************************************************** LOGOUT CODE SECTION ***************************************************************/
	/************************************************************* Author: Nathan Hernandez *************************************************************/
	/************************************************************************************************************************************************************/
	$validFunctions["logout"] = function($data,$db)
	{
		logout();
	};
	/* END SECTION */
	/************************************************ RESERVATION RETRIEVAL *****************************************************/
	/************************************************* Author: Chris Hegre *******************************************************************/
	/**************************************************************************************************************************************************************/
		
	$validFunctions["getReservations"] = function($data,$db)
	{
		$resvData = array();
		$query = "SELECT cr.acct_username,cr.bldg_name,cr.room_number,co.dpt_prefix as room_dept,resv_start,resv_id,resv_end,resv_size,resv_reason,acct_first_name,acct_last_name,ca.acct_type
		FROM cram_reservation cr JOIN cram_account ca ON ca.acct_username=cr.acct_username JOIN cram_room co on co.bldg_name=cr.bldg_name AND co.room_number=cr.room_number WHERE cr.resv_end>unix_timestamp() order by cr.resv_start";
		$result = $db->query($query);
		while ($row = $result->fetch_assoc()) {
		
			if (getUsername()==$row["acct_username"])
			{
				$row["acct_first_name"] = "You";
				$row["acct_last_name"] = "";
				$row["candelete"] = true;
			}
			if (getUsertype()=="student")
			{
				$row["acct_last_name"] = "";

			}
			else if (getUsertype()=="admin")
			{
				$row["candelete"] = true;
			}
			else if (getUsertype()=="professor")
			{
				if ($row["acct_type"] == "student" && getDepartment() == $row["room_dept"])
					$row["candelete"] = true;
			}
			
			$resvData[] = $row;
		}
		return json_encode($resvData); 
	};
	/************************************************ RESERVATION DELETION *****************************************************/
	/************************************************* Author: Chris Hegre *******************************************************************/
	/**************************************************************************************************************************************************************/
		
	$validFunctions["deleteReservation"] = function($data,$db)
	{
	
		if (!isset($data->resv_id)) die('getRooms error, expected resv_id');
		$resv_id = $db->real_escape_string($data->resv_id);
		
		$query = "SELECT co.dpt_prefix as room_dept,ca.dpt_prefix as user_dept,ca.acct_username,ca.acct_type,cr.resv_reason
		FROM cram_reservation cr JOIN cram_account ca ON ca.acct_username=cr.acct_username JOIN cram_room co on co.bldg_name=cr.bldg_name AND co.room_number=cr.room_number WHERE resv_id=$resv_id";
		$result = $db->query($query);
		$candelete = false;
		$row = $result->fetch_assoc();
		
		if (getUsername()==$row["acct_username"])
		{
			$candelete = true;
		}
		if (getUsertype()=="admin")
		{
			$candelete = true;
		}
		else if (getUsertype()=="professor")
		{
			if ($row["acct_type"] == "student" && getDepartment() == $row["room_dept"])
				$candelete = true;
		
		}
			
		if ($candelete)
		{
			$query = "DELETE FROM cram_reservation WHERE resv_id=$resv_id";
			$result = $db->query($query);
			if ($db->error != '')
			{
				echo (json_encode(false));
			}
			else
				echo (json_encode(true));
		
		}
		else
				echo (json_encode(false));
	};
	/************************************************ ERROR CHECKING/FLAGGING CODE SECTION *****************************************************/
	/************************************************************* Author: Sina Tashakkori *******************************************************************/
	/**************************************************************************************************************************************************************/
		
		$validFunctions["reserveRoom"] = function($data,$db)
		{	
			
			$isAvailTime = True;
			$isValidRoom =  True;
			
			if (!isset($data->bldg_name)) die(json_encode("reserveRoom error, expected bldg_name"));
			$bldg_name = $db->real_escape_string($data->bldg_name);
			
			if (!isset($data->room_num)) die(json_encode('reserveRoom error, expected room_num'));
			$bldg_name = $db->real_escape_string($data->room_num);
			
			$RoomNum = $data->room_num;
			$Name = $data->bldg_name;
			
			if (!isset($data->groupsize) || !is_numeric($data->groupsize))
				die(json_encode('Please provide a numerical group size'));
			
			$query = "SELECT * FROM cram_room WHERE room_number = '$RoomNum' AND bldg_name = '$Name'";
			$result = $db->query($query);
			if($result->num_rows == 0)
				die(json_encode("Could not locate building/room combo"));
				
			else
			{
				while ($row = $result->fetch_assoc()) 
				{
					$roomSeats = $row["room_seats"];
					if ($data->groupsize>$roomSeats)
						die(json_encode("Error: the room does not have enough seats for your group!"));
				}
			}
		
			if (!isset($data->resv_start)) die(json_encode('reserveRoom error, expected resv_start'));
			$resv_start = $db->real_escape_string($data->resv_start);
			if (!isset($data->resv_end)) die(json_encode('reserveRoom error, expected resv_end'));
			$resv_end = $db->real_escape_string($data->resv_end);
			
			if ($data->resv_start>$data->resv_end)
				die(json_encode('Your end time must be after your start time'));
			if ($data->resv_start==$data->resv_end)
				die(json_encode('Your end time must be after your start time'));
			if ($data->resv_start<time())
				die(json_encode('You can only reserve a room in the future'));
			
			
			
					
			if (!isset($data->reason) || strlen($data->reason) < 3)
				die(json_encode('Please provide a reason for your reservation'));
			
			
		$query = "SELECT * FROM cram_reservation WHERE (room_number = '$RoomNum' AND bldg_name = '$Name') AND ((resv_start BETWEEN $resv_start AND $resv_end OR resv_end BETWEEN $resv_start AND $resv_end) OR (resv_start>$resv_start AND resv_end<$resv_end))";
		$result = $db->query($query);

		if($result->num_rows != 0)
			die(json_encode("Sorry, the room you selected is already reserved at that time"));
		
		$uname = $db->real_escape_string(getUsername());
		$safename = $db->real_escape_string($Name);
		$groupsize = (int) $data->groupsize;
		$safereason = $db->real_escape_string($data->reason);
		$insert = "INSERT INTO cram_reservation (acct_username,room_number,bldg_name,resv_start,resv_end,resv_size,resv_reason) VALUES ('$uname',$RoomNum,'$safename',$resv_start,$resv_end,$groupsize,'$safereason')";
		
		$result = $db->query($insert);
		
		if ($db->error != '')
		{
			echo (json_encode(false));
		}
		else
			echo (json_encode(true));
	};	
	/* END SECTION */
	
	if (!isset($validFunctions[$function]))
		die('error invalid func');
	else
	{
		echo $validFunctions[$function]($data,$db);
	}
	
?>