<?php
//Get courseID list

function getCourseID($studentID) {
	require ('dbconnect.php');
	$courseIDSQL = mysqli_query($dbc,"SELECT courseID FROM StudentCourse WHERE studentID = '$studentID'");
	$courseIDList = [];
	while($courseID = mysqli_fetch_array($courseIDSQL,MYSQLI_ASSOC)) {
		array_push($courseIDList, $courseID['courseID']);
	}
	return json_encode($courseIDList);
}
$studentID = $_REQUEST["q"];
print_r (getCourseID($studentID));
?>