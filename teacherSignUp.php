
<?php //teacherSignUp.php
$page_title = 'Allocate Teacher';
include ('header.html');
$subject='';
$numblocks='';
$roomList=[];
$blockList=[];
$yearList=[];
$sizeList=[];
$teacherList=[];
$subjectName = '';
require ('dbconnect.php');
if(isset($_POST['submit'])){
	
	$subject=$_POST['subject'];
	$numblocks=$_POST['numblocks'];
	$roomList=$_POST['room'];
	$teacherList=$_POST['teacher'];
	$blockList=$_POST['block'];
	$yearList=$_POST['year'];
	$sizeList=$_POST['size'];
	$subjectName = $_POST['subjectName'];
	if (empty($subject)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in number of subject</strong></p>";
	}
	elseif (empty($numblocks)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in number of blocks</strong></p>";
	}
	elseif (empty($roomList)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in room</strong></p>";
	}
	elseif (empty($teacherList)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in teacher</strong></p>";
	}
	elseif (empty($blockList)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in block</strong></p>";
	}
	elseif (empty($yearList)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in year</strong></p>";
	}
	elseif (empty($sizeList)){
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in size</strong></p>";
	}
	elseif (empty($subjectName)){
		echo"<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p><strong>Fill in subject name</strong></p>";
	
	} else {
		for ($count = 0; $count < $numblocks; $count++) {
			$room = $roomList[$count];
			$teacher = $teacherList[$count];
			$block = $blockList[$count];
			$year = $yearList[$count];
			$size = $sizeList[$count];
			
			//Check if room is being used during block
			$roomCheck = mysqli_query($dbc,"SELECT roomID FROM roomTBL WHERE block = '$block'");
			$roomCheckAnswer = True;
			while($roomID = mysqli_fetch_array($roomCheck,MYSQLI_ASSOC)) {
				if ($room == $roomID['roomID']){
					$roomCheckAnswer = False;
					echo "<p><strong>Error - Room has already been allocated to this block</strong></p>";
				}
			}
			
			//Check if teacher is teaching during block
			$teacherCheck = mysqli_query($dbc,"SELECT teacherID FROM TeacherClassRoomCourse WHERE block = '$block'");
			$teacherCheckAnswer = True;
			while($teacherID = mysqli_fetch_array($teacherCheck,MYSQLI_ASSOC)) {
				if ($teacher == $teacherID['teacherID']){
					$teacherCheckAnswer = False;
					echo "<p><strong>Error - Teacher has already been allocated to this block</strong></p>";
				}
			}
			if ($teacherCheckAnswer && $roomCheckAnswer == True) {
				$classID = $teacherID+$subject+$block;
				$TeacherClassRoomCourse = "INSERT INTO TeacherClassRoomCourse(teacherID,block,roomID,courseID,year,classID)VALUES('$teacher','$block','$room','$subject','$year','$classID')";
				$roomTBL = "INSERT INTO roomTBL(roomID, size, block)VALUES('$room','$size','$block')";
				$TeacherBlock = "INSERT INTO TeacherBlock(teacherID,block)VALUES('$teacher','$block')";
				$subjectTBL = "INSERT INTO courseTBL(courseID,courseName)VALUES('$subject','$subjectName')";
				mysqli_query($dbc, $TeacherClassRoomCourse);
				mysqli_query($dbc, $roomTBL);
				mysqli_query($dbc, $TeacherBlock);
				mysqli_query($dbc, $subjectTBL);
			}
		
		}
		mysqli_close($dbc);
	}
}

?>		

<html>
<script>


function createTable() {

	document.getElementById('dtable').innerHTML = '';
	var Rows = document.getElementById("numblocks").value;

	var table = document.getElementById("dtable");

	var header = table.createTHead(0);
	
	var row = header.insertRow(0); 
	
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	
	cell1.innerHTML = "<b>RoomID</b>";
	cell2.innerHTML = "<b>TeacherID</b>";  
	cell3.innerHTML = "<b>Block</b>";
	cell4.innerHTML = "<b>Year</b>";
	cell5.innerHTML = "<b>Size</b>";
	
	
	//Table loop

	for (var count = 0; count < Rows; count++) {
		var row = table.insertRow(1);
		
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		var cell4 = row.insertCell(3);
		var cell5 = row.insertCell(4);
		
		//room,teacher,block,year,size input
		cell1.innerHTML = '<input id = "room" type = "text"  name="room[]" placeholder="" style="width: 60px;"/>';
		cell2.innerHTML ='<input id = "teacher" type = "text"  name="teacher[]" placeholder="" style="width: 60px;"/>';
		cell3.innerHTML ='<input id = "block" type = "text"  name="block[]" placeholder="" style="width: 60px;"/>';
		cell4.innerHTML ='<input id = "year" type = "text"  name="year[]" placeholder=""  style="width: 60px;"/>';
		cell5.innerHTML ='<input id = "size" type = "number"  name="size[]" placeholder="" style="width: 60px;"/><br />';
		}
}

</script>



<body>

<!--Subject and number of block inputs -->
<div id='div1'>
<form action="" method="post" class="basic-grey"/>
	<h2>Teacher Sign Up	</h2>
	

	<label>
	<label>	
		<span>SubjectID: </span>
		<input id = "subject" type = "text"  name="subject" placeholder="" />

	</label>
	<label>	
		<span>SubjectName: </span>
		<input id = "subjectName" type = "text"  name="subjectName" placeholder=""/>

	</label>
	</label>
	<label>	
		<span>Number of blocks: </span>
		<input id = "numblocks" type = "number"  name="numblocks" placeholder="" value="<?php echo $numblocks; ?>" onchange='createTable()'/>

	</label>
	<!--Creates room, teacher, block, year and size input table -->
	<div id='div3'><table id="dtable"></table></div> 
	
	<!-- Submit button -->
	<div id='div2'>
	<label>
	<span>&nbsp;</span>	
	<input type="submit" class="button" value="Submit" name = "submit" />
	</label>
	</div>
</form>
</div>





</html>
</body>	
<?php
include ('footer.html');
?>