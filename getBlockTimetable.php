<?php
//Get block list

function getBlock($studentID) {
	require ('dbconnect.php');
	$blockSQL = mysqli_query($dbc,"SELECT block FROM StudentCourse WHERE studentID = '$studentID'");
	$blockList = [];
	while($block = mysqli_fetch_array($blockSQL,MYSQLI_ASSOC)) {
		array_push($blockList, $block['block']);
	}
	return json_encode($blockList);
}
$studentID = $_REQUEST["q"];
print_r (getBlock($studentID));
?>