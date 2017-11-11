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
	<title>Details Settings</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>
<?php include ( "./inc/header.inc.php"); ?>
<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: details_update.php');
	}
}
else {
	die("You must be logged in to view this page!");
}
?>

<?php 

$updateinfo = @$_POST['updateinfo'];
$update = @$_POST['update'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT bio,queote FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_bio = $get_row['bio'];
$db_queote = $get_row['queote'];

//submit what the user type in database
if ($updateinfo) {
	$bio = $_POST['bio'];
	$bio = trim($bio);
	$bio = mysql_real_escape_string($bio);
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET bio='$bio' WHERE username='$user'");
		echo "<p class='error_echo'>Your Profile Bio Has Been Updated.</p>";
		header("Location: about.php?u=$user");
	}
if ($update) {
	$queote = $_POST['queote'];
	$queote = trim($queote);
	$queote = mysql_real_escape_string($queote);
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET queote='$queote' WHERE username='$user'");
		echo "<p class='error_echo'>Your Profile Queote Has Been Updated.</p>";
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
					<li><a href="workedu_update.php">Work and Education</a></li>
					<li><a href="cbinfo_update.php">Contac and Basic Info</a></li>
					<li><a href="location_update.php">Location and Places</a></li>
					<li style="border-bottom: none;"><a href="details_update.php" style="background-color: #0B810B; border-radius: 3px; color: #fff;">Details About</a></li>
				
				</ul>
			</div>
			<div class="settingsleftcontent">
				<?php include './inc/profilefooter.inc.php'; ?>
			</div>
		</li>
		<li style="float: right;">
			<div class="uiaccountstyle">
				<form action="#" method="post">
					<h2><p>Update Your Profile Info: </p></h2></br>
					ABOUT YOU: </br>
					<textarea name="bio" id="aboutyou" class="placeholder" style="margin: 10px; padding: 5px; width: 500px; height: 140px; resize: none;"> <?php echo $db_bio; ?> </textarea> </br>
					<input type="submit" name="updateinfo" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
					<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br></br>
					FAVORITE QUOTES: </br>
					<textarea name="queote" id="queote" class="placeholder" style="margin: 10px; padding: 5px; width: 500px; height: 140px; resize: none;"> <?php echo $db_queote; ?> </textarea> </br>
					<input type="submit" name="update" id="update" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
					<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
		</li>
	</ul>
</div>
</div>
</body>
</html>