<?php
//Get roomID list
function getRoomID($subjectID) {
	require ('dbconnect.php');
	$roomIDSQL = mysqli_query($dbc,"SELECT roomID FROM TeacherClassRoomCourse WHERE courseID = '$subjectID'");
	$roomIDList = [];
	while($roomID = mysqli_fetch_array($roomIDSQL,MYSQLI_ASSOC)) {
		array_push($roomIDList, $roomID['roomID']);
	}
	return json_encode($roomIDList);
}	
$subjectID = $_REQUEST["q"];
print_r (getRoomID($subjectID));
?>