<?php
//Get classID list
function getBlock($subjectID) {
	require ('dbconnect.php');
	$blockSQL = mysqli_query($dbc,"SELECT block FROM TeacherClassRoomCourse WHERE courseID = '$subjectID'");
	$blockList = [];
	while($block = mysqli_fetch_array($blockSQL,MYSQLI_ASSOC)) {
		array_push($blockList, $block['block']);
	}
	return json_encode($blockList);
}
$subjectID = $_REQUEST["q"];
print_r(getBlock($subjectID));
?>