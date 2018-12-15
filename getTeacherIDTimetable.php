<?php
//Get teacherID 

function getTeacherID($everythingArray) {
	require ('dbconnect.php');
	$length = count($everythingArray)/3;
	$teacherIDList = [];
	for  ($count = 0; $count < $length; $count++){
		$courseID = $everythingArray[$count];
		$block = $everythingArray[$count+$length];
		$roomID = $everythingArray[$count+$length+$length];
		$getTeacherID = "SELECT teacherID FROM TeacherClassRoomCourse WHERE courseID = '$courseID' && block = '$block' && roomID = '$roomID'";
		$teacherIDSQL = mysqli_query($dbc,$getTeacherID);
		while($teacherID = mysqli_fetch_array($teacherIDSQL,MYSQLI_ASSOC)) {
			array_push($teacherIDList, $teacherID['teacherID']);
		}
	}
	return json_encode($teacherIDList);
}
$everythingArray = json_decode($_REQUEST["q"]);
print_r(getTeacherID($everythingArray));
?>