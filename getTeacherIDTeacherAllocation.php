<?php
//Get teacherID list

function getTeacherID($subjectID) {
	require ('dbconnect.php');
	$teacherIDSQL = mysqli_query($dbc,"SELECT teacherID FROM TeacherClassRoomCourse WHERE courseID = '$subjectID'");
	$teacherIDList = [];
	while($teacherID = mysqli_fetch_array($teacherIDSQL,MYSQLI_ASSOC)) {
		array_push($teacherIDList, $teacherID['teacherID']);
	}
	return json_encode($teacherIDList);
}
$subjectID = $_REQUEST["q"];
print_r (getTeacherID($subjectID));
?>