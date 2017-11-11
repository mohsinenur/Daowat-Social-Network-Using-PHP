<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contact and Basic Info</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>
<?php include ( "./inc/header.inc.php"); ?>
<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: cbinfo_update.php');
	}
}
else {
	die("You must be logged in to view this page!");
}
?>

<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: cbinfo_update.php');
	}
}
$error = "";
$send = @$_POST['send'];
$sendemail = @$_POST['sendemail'];
$emaildata = @$_POST['emaildata'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT mobile,pub_email,email FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_mobile = $get_row['mobile'];
$db_pub_email = $get_row['pub_email'];
$db_email = $get_row['email'];


//submit what the user type in database

if ($sendemail) {
	$mobile = strip_tags(@$_POST['mobile']);
	$mobile = trim($mobile);
	$mobile = mysql_real_escape_string($mobile);
	$pub_email = strip_tags(@$_POST['pub_email']);
	$pub_email = trim($pub_email);
	$pub_email = mysql_real_escape_string($pub_email);
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET mobile='$mobile' WHERE username='$user'");
		$info_submit_query = mysql_query("UPDATE users SET pub_email='$pub_email' WHERE username='$user'");
		echo "<script>alert('Successfully Information Updated.')</script>";
		echo "<script>window.open('cbinfo_update.php','_self')</script>";
		$error = "<p class='succes_echo'>Information successfully updated.</p>";
	}



	
if ($emaildata) {
	$email = strip_tags(@$_POST['email']);
	$email = trim($email);
	$email = mysql_real_escape_string($email);
	$check_for_email = mysql_query("SELECT * FROM users WHERE email='$email'");
	$email_run = mysql_num_rows($check_for_email);
	if ($email == "") {
		$error = "<p class='error_echo'>Please type your daowat email!</p>";
	}else if ($email_run >= 1) {
		$error = "<p class='error_echo'>Already taken this email!</p>";
	}else {
		//getting email
		$check_for_email1 = mysql_query("SELECT * FROM users WHERE username='$user'");
		$uemail_fetch_query1 = mysql_fetch_assoc($check_for_email1 );
		$_SESSION['email'] = $uemail_fetch_query1 ['email'];
		//submit the form to database
		$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
		$info_submit_query = mysql_query("UPDATE users SET email='$email', confirmCode='$confirmCode' WHERE username='$user'");
		echo "<script>window.open('cbinfo_update.php','_self')</script>";
		$error = "<p class='succes_echo'>Email successfully changed.</p>";
						// send email
						$msg = "Successfully your Daowat email has been changed. 
						
						";
						mail($email ,"Daowat Email Changed",$msg, "From:Daowat <no-reply@daowat.com>");
					
		}
		
	}
?>

<div style="margin-top: 48px;">
<div style="width: 900px; margin: 0 auto;">
<?php echo $error; ?>
	<ul>
		<li style="float: left;">
			<div class="settingsleftcontent">
				<ul>
					<li><a href="profile_update.php">Profile Update</a></li>
					<li><a href="account_update.php">Account</a></li>
					<li><a href="password_update.php">Password</a></li>
					<li><a href="workedu_update.php">Work and Education</a></li>
					<li><a href="cbinfo_update.php" style="background-color: #0B810B; border-radius: 3px; color: #fff;">Contact and Basic Info</a></li>
					<li><a href="location_update.php">Location and Places</a></li>
					<li><a href="details_update.php">Details About</a></li>
				</ul>
			</div>
			<div class="settingsleftcontent">
				<?php include './inc/profilefooter.inc.php'; ?>
			</div>
		</li>
		<li style="float: right;">
			<div class="uiaccountstyle">
				<form action="cbinfo_update.php" method="POST">
				<h2><p>Mobile and Email</p></h2></br>
					Mobile: </br><input type="text" name="mobile" id="mobile" class="placeholder" size="43" value="<?php echo $db_mobile; ?>"> </br></br>
					Public Email: </br><input type="email" name="pub_email" id="pub_email" class="placeholder" size="43" value="<?php echo $db_pub_email; ?>"> </br></br>
					<input type="submit" name="sendemail" value="Update Information" title="Update Information" class="confirmSubmit">&nbsp;&nbsp;
					<input type="submit" name="no" value="Cancel" title="Cancel" class="cancelSubmit"></br>
				</form>
			</div>
			<div class="uiaccountstyle">
				<form action="cbinfo_update.php" method="POST">
				<h2><p>Change Daowat Email</p></h2></br>
					Daowat Email: </br><input type="email" name="email" id="email" class="placeholder" size="43" value="<?php echo $db_email; ?>"> </br></br>
					<input type="submit" name="emaildata" value="Update Information" title="Update Information" class="confirmSubmit">&nbsp;&nbsp;
					<input type="submit" name="no" value="Cancel" title="Cancel" class="cancelSubmit"></br>
				</form>
			</div>
		</li>
	</ul>
</div>
</div>
</body>
</html>