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
	<title>Work and Education</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>

<?php include ( "./inc/header.inc.php"); ?>

<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: workedu_update.php');
	}
}
else {
	die("You must be logged in to view this page!");
}
?>

<?php 

$updatework = @$_POST['updatework'];
$updateinfo = @$_POST['updateinfo'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT company, position, school, concentration FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_company = $get_row['company'];
$db_position = $get_row['position'];
$db_school = $get_row['school'];
$db_concentration = $get_row['concentration'];

//submit what the user type in database
if ($updatework) {
	$company = strip_tags(@$_POST['company']);
	$company = trim($company);
	$company = ucwords($company);
	$position = strip_tags(@$_POST['position']);
	$position = trim($position);
	$position = ucwords($position);
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET company='$company', position='$position' WHERE username='$user'");
		echo "<p class='error_echo'>Your Profile Information Has Been Updated.</p>";
		header("Location: about.php?u=$user");

}

if ($updateinfo) {
	$school = strip_tags(@$_POST['school']);
	$school = trim($school);
	$school = ucwords($school);
	$concentration = strip_tags(@$_POST['concentration']);
	$concentration = trim($concentration);
	$concentration = ucwords($concentration);

		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET school='$school', concentration='$concentration' WHERE username='$user'");
		echo "<p class='error_echo'>Your Profile Information Has Been Updated.</p>";
		header("Location: about.php?u=$user");
}

?>
<div style="margin-top: 48px;">
<div style="width: 900px; margin: 0 auto;">
	<ul>
		<li style="float: left;">
			<div class="settingsleftcontent">
				<ul>
					<li><a href="profile_update.php">Profile Update</a></li>
					<li><a href="account_update.php">Account</a></li>
					<li><a href="password_update.php">Password</a></li>
					<li><a href="workedu_update.php" style="background-color: #0B810B; border-radius: 3px; color: #fff;">Work and Education</a></li>
					<li><a href="cbinfo_update.php">Contact and Basic Info</a></li>
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
				<form action="workedu_update.php" method="post">
				<h2><p>Update Work: </p></h2></br>
				Company: </br><input type="text" name="company" id="company" class="placeholder" size="30" value="<?php echo $db_company; ?>"> </br></br>
				Position: </br><input type="text" name="position" id="position" class="placeholder" size="30" value="<?php echo $db_position; ?>"> </br></br>
				<input type="submit" name="updatework" id="updatework" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
			<div class="uiaccountstyle">
				<form action="workedu_update.php" method="post">
				<h2><p>Update Education: </p></h2></br>
				School: </br><input type="text" name="school" id="school" class="placeholder" size="30" value="<?php echo $db_school; ?>"> </br></br>
				Subject: </br><input type="text" name="concentration" id="concentration" class="placeholder" size="30" value="<?php echo $db_concentration; ?>"> </br></br>
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