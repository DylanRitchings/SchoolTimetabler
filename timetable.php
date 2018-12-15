<?php //timetable.php
$page_title = 'Timetable';
include ('header.html');
require('dbconnect.php');
$getNumberOfBlocks = "SELECT COUNT(*) FROM blocks";
$numberOfBlocks =  mysqli_fetch_row(mysqli_query($dbc, $getNumberOfBlocks))[0];
$getNumberOfPeriods = "SELECT COUNT(*) FROM periodSettings";
$numberOfPeriods =  mysqli_fetch_row(mysqli_query($dbc, $getNumberOfPeriods))[0];

//Get period start and end times
$periodStartList = [];
$periodEndList = [];
for ($count = 1; $count <= $numberOfPeriods; $count++) {
	$getPeriodStart = "SELECT periodStart FROM periodSettings WHERE period = '$count'";
	$periodStart = mysqli_fetch_row(mysqli_query($dbc, $getPeriodStart))[0];
	$getPeriodEnd = "SELECT periodEnd FROM periodSettings WHERE period = '$count'";
	$periodEnd = mysqli_fetch_row(mysqli_query($dbc, $getPeriodEnd))[0];
	array_push($periodStartList,$periodStart);
	array_push($periodEndList,$periodEnd);
}

//Get the blocks for each day
$mondayBlocks = [];
$tuesdayBlocks = [];
$wednesdayBlocks = [];
$thursdayBlocks = [];
$fridayBlocks = [];
for  ($count = 1; $count <= $numberOfPeriods; $count++){
	$getMonday = "SELECT block FROM BlockPeriodDay WHERE period = '$count' && day = 'Monday'";
	$monday = mysqli_fetch_row(mysqli_query($dbc, $getMonday))[0];
	array_push($mondayBlocks,$monday);
	
	$getTuesday = "SELECT block FROM BlockPeriodDay WHERE period = '$count' && day = 'Tuesday'";
	$tuesday = mysqli_fetch_row(mysqli_query($dbc, $getTuesday))[0];
	array_push($tuesdayBlocks,$tuesday);
	
	$getWednesday = "SELECT block FROM BlockPeriodDay WHERE period = '$count' && day = 'Wednesday'";
	$wednesday = mysqli_fetch_row(mysqli_query($dbc, $getWednesday))[0];
	array_push($wednesdayBlocks,$wednesday);
	
	$getThursday = "SELECT block FROM BlockPeriodDay WHERE period = '$count' && day = 'Thursday'";
	$thursday = mysqli_fetch_row(mysqli_query($dbc, $getThursday))[0];
	array_push($thursdayBlocks,$thursday);
	
	$getFriday = "SELECT block FROM BlockPeriodDay WHERE period = '$count' && day = 'Friday'";
	$friday = mysqli_fetch_row(mysqli_query($dbc, $getFriday))[0];
	array_push($fridayBlocks,$friday);
	
}

?>

<script>

function getCourseID(studentID){

	//Get the courseIDs for the subjects the student does
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					resolve(this.responseText);
				}
			}
			xmlhttp.open("GET", "getCourseIDTimetable.php?q=" + studentID, true);
			xmlhttp.send(); 
		});
	}
	doXHRPromise().then(function(responseText) {
		var courseIDList = JSON.parse(responseText);
		getBlock(studentID,courseIDList);
	});
}


function getBlock(studentID,courseIDList){
	//Get the blocks for the subjects the student does
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					resolve(this.responseText);
				}
			}
			xmlhttp.open("GET", "getBlockTimetable.php?q=" + studentID, true);
			xmlhttp.send(); 
		});
	}
	doXHRPromise().then(function(responseText) {
		var blockList = JSON.parse(responseText);
		getRoomID(studentID,courseIDList,blockList);
	});
}

function getRoomID(studentID,courseIDList,blockList){
	//Get the rooms for the subjects the student does
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					resolve(this.responseText);
				}
			}
			xmlhttp.open("GET", "getRoomIDTimetable.php?q=" + studentID, true);
			xmlhttp.send(); 
		});
	}
	
	doXHRPromise().then(function(responseText) {
		var roomIDList = JSON.parse(responseText);
		getTeacherID(courseIDList,blockList,roomIDList);
	});
}

function getTeacherID(courseIDList,blockList,roomIDList){
	//Get the rooms for the subjects the student does
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					resolve(this.responseText);
				}
			}
			var everythingArray = courseIDList.concat(blockList).concat(roomIDList);
			xmlhttp.open("GET", "getTeacherIDTimetable.php?q=" + JSON.stringify(everythingArray), true);
			xmlhttp.send(); 
		});
	}
	doXHRPromise().then(function(responseText) {
		var teacherIDList = JSON.parse(responseText);
		createTimeTable(courseIDList,blockList,roomIDList,teacherIDList);
	});
	
}

function createTimeTable(courseIDList,blockList,roomIDList,teacherIDList){
	var mondayBlocks = <?php echo json_encode($mondayBlocks);?>;
	var tuesdayBlocks = <?php echo json_encode($tuesdayBlocks);?>;
	var wednesdayBlocks = <?php echo json_encode($wednesdayBlocks);?>;
	var thursdayBlocks = <?php echo json_encode($thursdayBlocks);?>;
	var fridayBlocks = <?php echo json_encode($fridayBlocks);?>;
	var periodStartList = <?php echo json_encode($periodStartList);?>;
	var periodEndList = <?php echo json_encode($periodEndList);?>;
	var numberOfPeriods = <?php echo json_encode($numberOfPeriods);?>;
	
	document.getElementById('timetable').innerHTML = '';
	var table = document.getElementById("timetable");
	var header = table.createTHead(0);
	var row = header.insertRow(0); 
	
	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);
	
	cell0.innerHTML = "start - <br>end";
	cell1.innerHTML = "Monday";
	cell2.innerHTML = "Tuesday";
	cell3.innerHTML = "Wednesday";
	cell4.innerHTML = "Thursday";
	cell5.innerHTML = "Friday";
	
	for (count = 0; count < numberOfPeriods; count++){
		var row = table.insertRow();
		
		var cell0 = row.insertCell(0);
		var cell1 = row.insertCell(1);
		var cell2 = row.insertCell(2);
		var cell3 = row.insertCell(3);
		var cell4 = row.insertCell(4);
		var cell5 = row.insertCell(5);
		
		var periodTime = periodStartList[count] + ' - <br>' + periodEndList[count];
		cell0.innerHTML = periodTime;
		//Monday
		var position = blockList.indexOf(mondayBlocks[count]);
		if (position != -1) {
			cell1.innerHTML = 'Course: ' + courseIDList[position] + '<br>Teacher: ' + teacherIDList[position] + '<br>Room: ' + roomIDList[position] + '<br>Block: ' + blockList[position];	
		}
		//Tuesday
		var position = blockList.indexOf(tuesdayBlocks[count]);
		if (position != -1) {
			cell2.innerHTML = 'Course: ' + courseIDList[position] + '<br>Teacher: ' + teacherIDList[position] + '<br>Room: ' + roomIDList[position] + '<br>Block: ' + blockList[position];	
		}
		//Wednesday
		var position = blockList.indexOf(wednesdayBlocks[count]);
		if (position != -1) {
			cell3.innerHTML = 'Course: ' + courseIDList[position] + '<br>Teacher: ' + teacherIDList[position] + '<br>Room: ' + roomIDList[position] + '<br>Block: ' + blockList[position];	
		}
		//Thursday
		var position = blockList.indexOf(thursdayBlocks[count]);
		if (position != -1) {
			cell4.innerHTML = 'Course: ' + courseIDList[position] + '<br>Teacher: ' + teacherIDList[position] + '<br>Room: ' + roomIDList[position] + '<br>Block: ' + blockList[position];	
		}		
		//Friday
		var position = blockList.indexOf(fridayBlocks[count]);
		if (position != -1) {
			cell5.innerHTML = 'Course: ' + courseIDList[position] + '<br>Teacher: ' + teacherIDList[position] + '<br>Room: ' + roomIDList[position] + '<br>Block: ' + blockList[position];	
		}		
	}
}
</script>

<label>	
	<span>Student ID: </span>
	<input id = "studentID" type = "text"  name="studentID" placeholder="" style="width: 60px;" onchange = 'getCourseID(this.value)'/>
	
</label> 
<div id='div'><table id="timetable"></table></div> 
<?php
include ('footer.html');
?>