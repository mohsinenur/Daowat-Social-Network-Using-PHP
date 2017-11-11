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
	<title>Account Settings</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>
<?php include ( "./inc/header.inc.php"); ?>
<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: account_update.php');
	}
}
else {
	die("You must be logged in to view this page!");
}
?>

<?php 
$error = "";
$updateinfo = @$_POST['updateinfo'];
$update = @$_POST['update'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT first_name,nick_name FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_firstname = $get_row['first_name'];
$db_nickname = $get_row['nick_name'];

//submit what the user type in database
if ($updateinfo) {
	$firstname = strip_tags(@$_POST['fname']);
	$firstname = trim($firstname);
	$firstname = mysql_real_escape_string($firstname);
	$firstname = ucwords($firstname);

	if(strlen($firstname) < 7 || strlen($firstname) > 20 )  {
		$error = "<p class='error_echo'>Your Fullname must be 8-20 characters long.</p>";
	}else {
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET first_name='$firstname' WHERE username='$user'");
		$error = "<p class='error_echo'>Your Profile Information Has Been Updated.</p>";
		header("Location: $user");
	}

}

?>
<div style="margin-top: 48px;">
<div style="width: 900px; margin: 0 auto;">
	<ul>
	<?php echo $error; ?>
		<li style="float: left;">
		
			<div class="settingsleftcontent">
				<ul>
					<li><a href="profile_update.php">Profile Update</a></li>
					<li><a href="account_update.php" style="background-color: #0B810B; border-radius: 3px; color: #fff;">Account</a></li>
					<li><a href="password_update.php">Password</a></li>
					<li><a href="workedu_update.php">Work and Education</a></li>
					<li><a href="cbinfo_update.php">Contact and Basic Info</a></li>
					<li><a href="location_update.php">Location and Places</a></li>
					<li><a href="details_update.php">Details About</a></li>
				</ul>
			</div>
			<div class="settingsleftcontent">
				<p style="text-align: center; padding: 10px 0px;">Mohsin E Nur production &copy; 2015 | Daowat</p>
			</div>
		</li>
		<li style="float: right;">
			<div class="uiaccountstyle">
				<form action="account_update.php" method="post">
				<h2><p>Change your name: </p></h2></br>
				Your Full Name: </br><input type="text" name="fname" id="fname" class="placeholder" size="30" value="<?php echo $db_firstname; ?>"> </br></br>
				<input type="submit" name="updateinfo" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
		</li>
	</ul>
</div>
</div>
</body>
</html>