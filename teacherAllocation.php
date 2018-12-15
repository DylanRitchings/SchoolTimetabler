<?php //teacherAllocation.php
$page_title = 'Teacher Allocation';


include ('header.html');
require ('dbconnect.php');
$subjectID='';
$valuesList=[];
$teacherIDList = [];
$roomIDList = [];
$classIDList = [];
//Get courseID for courseID drop down
$courseIDs = mysqli_query($dbc,'SELECT courseID FROM courseTBL');
$courseIDList = [];
while($courseID = mysqli_fetch_array($courseIDs,MYSQLI_ASSOC)){
	array_push($courseIDList, $courseID['courseID']);
	}
	
if(isset($_GET['submit'])){	
	$deleteList=$_REQUEST['DELETE'];
	$courseID = $_REQUEST['subject'];
	for ($count = 0; $count < count($deleteList); $count++) {
		$delete = $deleteList[$count];
		$deleteSplit = explode('--',$delete);
		$teacherID = $deleteSplit[0];
		$block = $deleteSplit[1];
		$roomID = $deleteSplit[2];
		$deleteTeacherClassRoomCourse = "DELETE FROM TeacherClassRoomCourse WHERE teacherID = '$teacherID' && block = '$block' && roomID = '$roomID' && courseID = '$courseID'";
		mysqli_query($dbc,$deleteTeacherClassRoomCourse);
		$deleteRoomTBL = "DELETE FROM roomTBL WHERE block = '$block' && roomID = '$roomID'";
		mysqli_query($dbc,$deleteRoomTBL);
	}
}
mysqli_close($dbc);

?>



<html>
<head>

<script>



	
//getTeacherIDList
function getTeacherIDList(subjectID){
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				resolve(this.responseText);
				}
			}
			xmlhttp.open("GET", "getTeacherIDTeacherAllocation.php?q=" + subjectID, true);
			xmlhttp.send(); 
		});
	}

	doXHRPromise().then(function(responseText) {
		var teacherIDList = JSON.parse(responseText);
		getBlockList(subjectID,teacherIDList);
		
	});
}

//getBlockList
function getBlockList(subjectID,teacherIDList){
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				resolve(this.responseText);
				}
			}
		xmlhttp.open("GET", "getBlockTeacherAllocation.php?q=" + subjectID, true);
		xmlhttp.send(); 
		});
	}
	
	doXHRPromise().then(function(responseText) {
		var blockList = JSON.parse(responseText);
		getRoomIDList(subjectID,teacherIDList,blockList);
		
	});
}

//getRoomIDList
function getRoomIDList(subjectID,teacherIDList,blockList){
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				resolve(this.responseText);
				}
			}
		xmlhttp.open("GET", "getRoomIDTeacherAllocation.php?q=" + subjectID, true);
		xmlhttp.send(); 
		});
	}
	
	doXHRPromise().then(function(responseText) {
		var roomIDList = JSON.parse(responseText);
		isRoomFull(subjectID,teacherIDList,blockList,roomIDList);
		
	});
}

//isRoomFull
function isRoomFull(subjectID,teacherIDList,blockList,roomIDList){
	function doXHRPromise() {
		return new Promise(function(resolve, reject) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				resolve(this.responseText);
				}
			}
		var everythingList = roomIDList.concat(blockList);
		xmlhttp.open("GET", "isRoomFullTeacherAllocation.php?q=" + JSON.stringify(everythingList), true);
		xmlhttp.send(); 
		});
	}
	
	doXHRPromise().then(function(responseText) {
		var roomFullList = JSON.parse(responseText);
		createTable(subjectID,teacherIDList,blockList,roomIDList,roomFullList);
		
	});
}



//create table
function createTable(subjectID,teacherIDList,blockList,roomIDList,roomFullList) {;
	document.getElementById('teacherTable').innerHTML = '';
	var table = document.getElementById("teacherTable");
	var header = table.createTHead(0);
	
	var row = header.insertRow(0); 
	
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	
	cell1.innerHTML = "<b>DELETE</b>"
	cell2.innerHTML = "<b>TeacherID</b>";
	cell3.innerHTML = "<b>Block</b>";  
	cell4.innerHTML = "<b>RoomID</b>";
	cell5.innerHTML = "<b>Is Full?</b>";
	
	for (var count = 0; count < teacherIDList.length; count++) {
		var row = table.insertRow(1);
		
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		var cell4 = row.insertCell(3);
		var cell5 = row.insertCell(4);
		
		cell1.innerHTML = '<input type = "checkbox" name = "DELETE[]" class = "DELETE" value = "' + teacherIDList[count] + '--' + blockList[count] + '--' + roomIDList[count]+ '">';
		cell2.innerHTML = teacherIDList[count];
		cell3.innerHTML = blockList[count];
		cell4.innerHTML = roomIDList[count];
		cell5.innerHTML = roomFullList[count];
	}
}
function everything(subjectID){
	getTeacherIDList(subjectID);	
}
</script>
</head>

<form>

<body>	
	<label>
	<!-- courseID drop down menu -->
	<select name = "subject" id = "subject" onchange="everything(this.value);">
	
		<option selected='true' value="" disabled selected>SubjectID</option>
		<?php foreach($courseIDList as $courseID => $value) {
			echo '<option value = "'.$value.'">'.$value.'</option>';
		}
		?>
	</select>
	</label>
	
<div id='div3'><table id="teacherTable"></table></div> 

<label>
<span>&nbsp;</span>	
<input type="submit" class="button" value="Submit" name = "submit" />
</label>	

<form>

</html>
<?php
include ('footer.html');
?>	