<?php //settings.php
$page_title = 'Settings';
include ('header.html');
$numberOfPeriods ='';
$startTimeList = [];
$endTimeList = [];
$numberOfBlocks = '';
$blockList = [];

if(isset($_POST['submit'])){
	$startTimeList=$_POST['startTime'];
	$endTimeList =$_POST['endTime'];
	$blockList =$_POST['block'];
	$numberOfBlocks = $_POST['numberOfBlocks'];
	$numberOfPeriods = $_POST['numberOfPeriods'];
	require ('dbconnect.php');
	$clearTable = "TRUNCATE TABLE periodSettings";
	$clear = mysqli_query($dbc, $clearTable);
	$clearTable = "TRUNCATE TABLE BlockPeriodDay";
	$clear = mysqli_query($dbc, $clearTable);
	$clearTAble = "TRUNCATE TABLE blocks";
	$clear = mysqli_query($dbc, $clearTable);
	for ($count = 0; $count < $numberOfPeriods; $count++) {
		$startTime = $startTimeList[$count];
		$endTime = $endTimeList[$count];
		$period = $count + 1;
		$periodSettings = "INSERT INTO periodSettings(period,periodStart,periodEnd)VALUES('$period','$startTime','$endTime')";
		mysqli_query($dbc, $periodSettings);
	}
	for ($count1 = 0; $count1 < $numberOfPeriods; $count1 ++){
		for ($count2 = 0; $count2 < 5; $count2 ++){
			$block = $blockList[($count1*5)+$count2];
			$period = $count1 + 1;
			if ($count2 == 0){
				$day = "Monday";
			}
			if ($count2 == 1){
				$day = "Tuesday";
			}
			if ($count2 == 2){
				$day = "Wednesday";
			}
			if ($count2 == 3){
				$day = "Thursday";
			}
			if ($count2 == 4){
				$day = "Friday";
			}
			$BlockPeriodDay = "INSERT INTO BlockPeriodDay(block,period,day)VALUES('$block','$period','$day')";
			mysqli_query($dbc, $BlockPeriodDay);
			$blocksSQL = "INSERT INTO blocks(block)VALUES('$block')";
			mysqli_query($dbc, $blocksSQL);
		}
	}
	mysqli_close($dbc);
}
?>


<html>
<script>


function createPeriodTable(numberOfPeriods) {
	document.getElementById('periodTable').innerHTML = '';
	var table = document.getElementById("periodTable");

	
	//Header
	var header = table.createTHead(0);
	var row = header.insertRow(0);
	var cell = row.insertCell(0);
	cell.innerHTML = "Period: ";
    for (var count = 0; count<numberOfPeriods; count++) {
		var cell = row.insertCell(count+1);
		cell.innerHTML = (count+1).toString();
	}
	
	//Start time input
	var row = table.insertRow(1);
	var cell = row.insertCell(0);
	cell.innerHTML = "Start time: ";
	for (var count = 0; count<numberOfPeriods; count++) {
		var cell = row.insertCell(count+1);
		cell.innerHTML = '<input id = "startTime" type = "time"  name="startTime[]" placeholder=""  style="width: 100px;"/>';
	}
	
	//End time input
	var row = table.insertRow(2);
	var cell = row.insertCell(0);
	cell.innerHTML = "End time: ";
	for (var count = 0; count<numberOfPeriods; count++) {
		var cell = row.insertCell(count+1);
		cell.innerHTML = '<input id = "endTime" type = "time"  name="endTime[]" placeholder=""  style="width: 100px;"/>';
	}
}

function createBlockTable(numberOfBlocks,numberOfPeriods){
	
	document.getElementById('blockTable').innerHTML = '';
	var table = document.getElementById("blockTable");
	
	var header = table.createTHead(0);
	//Title Row
	var row = header.insertRow(0); 
	
	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);
	
	cell0.innerHTML = "Period";
	cell1.innerHTML = "Mon";
	cell2.innerHTML = "Tue";
	cell3.innerHTML = "Wed";
	cell4.innerHTML = "Thu";
	cell5.innerHTML = "Fri";
	
	for (var count = 0; count<numberOfPeriods; count++) {
		var row = table.insertRow(count+1);
		
		//period column
		var cell0 = row.insertCell(0);
		cell0.innerHTML = count+1;
		
		//loops for number of days
		for (var count2 = 1; count2<6; count2++) {
			blockList = [];
			var cell = row.insertCell(count2);
			for (var count3 = 0; count3<numberOfBlocks; count3++) {
				var block = String.fromCharCode(65 + count3);
				blockList.push(block);
				
					
			}
			selectBox = ['<select  name = "block[]"><option selected="true" value="" disabled selected>Block</option>'];
			for (var count4 = 0; count4<numberOfBlocks; count4++){
				selectBox.push('<option value = "');
				selectBox.push(blockList[count4]);
				selectBox.push('">');
				selectBox.push(blockList[count4]);
				selectBox.push('</option>');
			}
			selectBox.push('<option value = "0">NONE</option>');
			cell.innerHTML = selectBox.join("");
		}
	}
	
}
</script>


<body>
<form action="" method="post" class="basic-grey"/>
<h2>Settings</h2>
<label>	
	<span>Number Of Periods: </span>
	<input id = "numberOfPeriods" type = "number"  name="numberOfPeriods" id = "numberOfPeriods" placeholder="" value="<?php echo $numberOfPeriods; ?>" style="width: 60px;" onchange = 'createPeriodTable(this.value)'/>
</label
	<!-- Period Time Table -->
	<div id='div1'><table id="periodTable"></table></div> 
	
	<span>Number Of Blocks: </span>
	<input id = "numberOfBlocks" type = "number"  name="numberOfBlocks" placeholder="" value="<?php echo $numberOfBlocks; ?>" style="width: 60px;" onchange = 'createBlockTable(this.value,document.getElementById("numberOfPeriods").value)'/>
	
	<div id='div1'><table id="blockTable"></table></div> 
	
	<div id='div2'>
	<label>
	<span>&nbsp;</span>	
	<input type="submit" class="button" value="Submit" name = "submit" />
	</label>
	</div>

</body>
</html>

<?php
include ('footer.html');
?>