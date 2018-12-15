<?php
//Get roomFull list
function isRoomFull($everythingList) {
	require ('dbconnect.php');
	$roomFullList = [];
	for ($count = 0; $count < count($everythingList)/2; $count++){
		$roomID = $everythingList[$count];
		$block = $everythingList[$count+count($everythingList)/2];
		$roomSize = mysqli_fetch_row(mysqli_query($dbc,"SELECT size FROM roomTBL WHERE roomID = '$roomID' && block = '$block'"))[0];
		$amountOfStudents = mysqli_fetch_row(mysqli_query($dbc, "SELECT COUNT(*) FROM StudentCourse WHERE roomID = '$roomID' && block = '$block'"))[0];
		if ($roomSize > $amountOfStudents) {
			array_push($roomFullList, 'NOT FULL');
		}
		else if ($roomSize = $amountOfStudents) {
			array_push($roomFullList, 'FULL');
		}
		else if ($roomSize < $amountOfStudents) {
			array_push($roomFullList, 'OVER BOOKED');
		}
	}
	return json_encode($roomFullList);
}
	
$everythingList =json_decode( $_REQUEST["q"]);
print_r (isRoomFull($everythingList));
?>