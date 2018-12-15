<?php

$page_title = 'Reading';
include('header.html');

?>
<h1>All records in table</h1>
<?php
require 'dbconnect.php';
	$sql = 'SELECT * FROM studenttbl';
	$result = mysqli_query($dbc,$sql);
	if ($result)
	    {
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				echo "Id: " . $row['studentid']. '</br>';
				echo 'First Name: ' . $row['firstname'].'</br>';
				echo 'Surname: ' . $row['surname'].'</br>';
				echo 'DOB: ' . $row['dob'].'</br>';
				echo 'Phone Number: ' . $row['phonenumber'].'</br>';
				echo 'Email: ' . $row['email'].'</br>';
				echo '</br></br>';
				}
		}
	else
	    {
		echo 'No results';
		}
	mysqli_close($dbc);
?>
<?php
include('footer.html');
?>