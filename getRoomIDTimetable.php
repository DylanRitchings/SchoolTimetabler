<?php
//Get roomID list

function getRoomID($studentID) {
	require ('dbconnect.php');
	$roomIDSQL = mysqli_query($dbc,"SELECT roomID FROM StudentCourse WHERE studentID = '$studentID'");
	$roomIDList = [];
	while($roomID = mysqli_fetch_array($roomIDSQL,MYSQLI_ASSOC)) {
		array_push($roomIDList, $roomID['roomID']);
	}
	return json_encode($roomIDList);
}
$studentID = $_REQUEST["q"];
print_r (getRoomID($studentID));
?>