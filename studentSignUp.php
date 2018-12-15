<?php //studentSignUp.php
$page_title = 'Pick Subjects';
include ('header.html');
require ('dbconnect.php');

$studentID='';
$subject=[];
$blockList=[];
$year='';

//Get number of blocks
$getNumberOfBlocks = "SELECT COUNT(*) FROM blocks";
$result1 = mysqli_query($dbc,$getNumberOfBlocks);
$amount = mysqli_fetch_row($result1);
$numblocks = $amount[0];

//Creating subject dictionary
$subjectArray = [];
for ($count = 0; $count <= $numblocks; $count++) {
	
	//Change count to ascii character
	$block = chr(65 + $count);
	
	//numSubjects used as second loop length
	$getNumberOfSubjects = "SELECT COUNT(*) FROM TeacherClassRoomCourse WHERE block = '$block'" ;
	$result2 = mysqli_query($dbc,$getNumberOfSubjects);
	$amount = mysqli_fetch_row($result2);
	$numsubject = $amount[0];
	
	$getCourseID = "SELECT courseID FROM TeacherClassRoomCourse WHERE block = '$block'";
	$result3 = mysqli_query($dbc,$getCourseID);

	$subjectList = [];
	while($subject = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
		array_push($subjectList, $subject['courseID']);
	
	}

	for ($count2 = 0;$count2 < $numsubject; $count2++) {
		$subjectID = $subjectList[$count2];
		$getCourseName = "SELECT courseName FROM courseTBL WHERE courseID = '$subjectID'";
		$subjectNameResult = mysqli_query($dbc,$getCourseName);
		$subjectName = mysqli_fetch_assoc($subjectNameResult);
	$subjectArray[$block][$count2] = $subjectName['courseName'];
	}
}

if ($numblocks)
		{
		echo '<script type="text/javascript">',
			'createTable();',
			'</script>';
			}
else
		{
		echo "<p><strong>Error</strong></p>";
			}

if(isset($_GET['submit'])){
	
	$studentID=$_REQUEST['studentID'];
	$subjectBlockList = $_REQUEST['subject'];
	$year = $_REQUEST['year'];
	
	if (empty($studentID)){
	echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
	echo "<p><strong>Fill in number of studentID</strong></p>";
	} else {

		
		$clearStudentTBL = "DELETE FROM studentTBL WHERE studentID = '$studentID'";
		mysqli_query($dbc,$clearStudentTBL);
		$clearStudentCourse = "DELETE FROM StudentCourse WHERE studentID = '$studentID'";
		mysqli_query($dbc,$clearStudentCourse);
		$studentTBL = "INSERT INTO studentTBL (yearGroup, studentID)VALUES('$year','$studentID')";
		mysqli_query($dbc,$studentTBL);
		for ($count = 0; $count < count($subjectBlockList); $count++) {
			$subjectBlock = $subjectBlockList[$count];
			$subjectBlockSplit = explode('0',$subjectBlock);
			$subject = $subjectBlockSplit[0];
			$block = $subjectBlockSplit[1];
			$getCourseID = mysqli_query($dbc,"SELECT courseID FROM courseTBL WHERE courseName = '$subject'");
			$courseIDResult = mysqli_fetch_assoc($getCourseID);
			$courseID = $courseIDResult['courseID'];
			$getRoomID = mysqli_query($dbc,"SELECT roomID FROM TeacherClassRoomCourse WHERE block = '$block' && courseID = '$courseID'");
			$RoomIDResult = mysqli_fetch_assoc($getRoomID);
			$roomID = $RoomIDResult['roomID'];
			$studentCourse = "INSERT INTO StudentCourse (studentID,courseID,block,roomID)VALUES('$studentID','$courseID','$block','$roomID')";
			mysqli_query($dbc,$studentCourse);
		}
	}
}
mysqli_close($dbc);
?>


<html>

<script>

function createTable() {
	var numblocks = <?php echo $numblocks; ?>;
	var Rows = parseInt(numblocks);
	document.getElementById('dtable').innerHTML = '';
	var table = document.getElementById("dtable")
	var header = table.createTHead(0);
	var row = header.insertRow(0);
	
	//Table header
	for (var count = 0; count < Rows; count++) { 
		var character = String.fromCharCode(65 + count);
		var cell = row.insertCell(count);
		cell.innerHTML =('<b>'+character+'</b>');
	}
	
	
	
	var subjectArray = <?php echo json_encode($subjectArray);?>;
	var numblocks = Object.keys(subjectArray).length;
	subjectLengthArray = [];
	
	
	//Get amount of subjects in each block
	for (var count2 = 0; count2 < numblocks; count2++) {
		character = String.fromCharCode (65 + count2);
		test = subjectArray[character];
		if (test != undefined) {
			subjectLengthArray.push(subjectArray[character].length);
		}
	}
	//Get largest amount of subjects in one block value
	subjectLength = Math.max.apply(null,subjectLengthArray);
	
	//create each cell
	for (var count2 = 0; count2< subjectLength; count2++){
		var row = table.insertRow(count2+1); 
		for (var count3 = 0; count3 < numblocks; count3++){
			character = String.fromCharCode (65 + count3);
			test = subjectArray[character][count2];
			if (test != undefined) {
				var cell = row.insertCell(count3);
				cell.innerHTML = ('<input type = "checkbox" name = "subject[]" class = "subject" value = "' + subjectArray[character][count2] + '0' + character + '">' +subjectArray[character][count2] + '<br>')
			}
		}
	}
}


</script>
<form>
<h2>Pick one subject per block  </h2>

<body onload="createTable()">
<!-- Student ID input -->
<label>	
	<span>Student ID: </span>
	<input id = "studentID" type = "text"  name="studentID" placeholder="" style="width: 60px;"/>

</label> 

<!-- Year Group input -->
<label>	
	<span>Year Group: </span>
	<input id = "year" type = "number"  name="year" placeholder="" style="width: 60px;"/>

</label> 

<!-- Subject Table -->
<div id='div1'><table id="dtable"></table></div> 

<!-- Submit button -->
<label>
<span>&nbsp;</span>	
<input type="submit" class="button" value="Submit" name = "submit" />
</label>
</body>
</form>
</html>



<?php
include ('footer.html');
?>