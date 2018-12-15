<?php //signup.php
$page_title = 'Sign Up';
include ('header.html');
$fname='';
$sname='';
$email='';
if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
	$fname=$_POST['fname'];
	$sname=$_POST['sname'];
	$email=$_POST['email'];
	$course=$_POST['course'];

	if (empty($email)){
		echo "<h1>Sign up for a course</h1>";
		echo "<p><strong>Error - you have not filled in all of the required fields</strong></p>";
		echo "<p>Please enter your email address.</p>";
		
	} else {
		require 'dbconnect.php';
		$sql = "INSERT INTO signup(firstname,surname,email,course,signupdate)VALUES('$fname','$sname','$course','$email',NOW())";
		$result = mysqli_query($dbc, $sql);
		if ($result)
			{
			echo "<p><strong> Thank you for signing up</strong></p>";
			echo "<h1>Sign up for a course</h1>";
			}
		else
			{
			echo "<p><strong>Error</strong></p>";
			}
		mysqli_close($dbc);
		
	}
}
?>

<form action="signup.php" method="post" class="basic-grey">
	<h2>Sign-Up Form
		<span>Please complete all of the field.</span>
	</h2>
	<label>
		<span>Your First Name: </span>
		<input id = "fname" type = "text"  name="fname" placeholder="Your first name(s)" value="<?php echo $fname; ?>" />
	</label>
	<label>
		<span>Your Surname: </span>
		<input id = "sname" type = "text"  name="sname" placeholder="Your surname" value="<?php echo $sname; ?>" />
	</label>
	<label>
		<span>Your Email: </span>
		<input id = "email" type = "email"  name="email" placeholder="Valid Email Address" value="<?php echo $email; ?>" />
	</label>
	
	<label>
		<span>Course: </span><select name="course" >
		<option value="computerscience" selected="selected">Computer Science</option>
		<option value="maths" selected="selected">Maths</option>
	</label>
	<label>
		<span>&nbsp;</span>
		<input type="submit" class="button" value="Submit" />
	</label>
</form>
<?php
include ('footer.html');
?>