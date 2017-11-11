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
	<title>Location Settings</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>

<?php include ( "./inc/header.inc.php"); ?>

<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: location_update.php');
	}
}
$error = "";
$send = @$_POST['send'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT country,city,hometown FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_country = $get_row['country'];
$db_city = $get_row['city'];
$db_hometown = $get_row['hometown'];

//submit what the user type in database

if ($send) {
	$country = strip_tags(@$_POST['country']);
	$city = strip_tags(@$_POST['city']);
	$city = trim($city);
	$city = mysql_real_escape_string($city);
	$city = ucwords($city);
	$hometown = strip_tags(@$_POST['hometown']);
	$hometown = trim($hometown);
	$hometown = mysql_real_escape_string($hometown);
	$hometown = ucwords($hometown);
		//submit the form to database
		$info_submit_query = mysql_query("UPDATE users SET city='$city' WHERE username='$user'");
		$info_submit_query = mysql_query("UPDATE users SET country='$country' WHERE username='$user'");
		$info_submit_query = mysql_query("UPDATE users SET hometown='$hometown' WHERE username='$user'");
		echo "<script>alert('Successfully Information Updated.')</script>";
		echo "<script>window.open('location_update.php','_self')</script>";
		$error = "<p class='succes_echo'>Information successfully changed.</p>";
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
					<li><a href="cbinfo_update.php">Contact and Basic Info</a></li>
					<li><a href="location_update.php" style=" background-color: #0B810B; border-radius: 4px; color: #fff;">Location and Places</a></li>
					<li><a href="details_update.php">Details About</a></li>
				</ul>
			</div>
			<div class="settingsleftcontent">
				<?php include './inc/profilefooter.inc.php'; ?>
			</div>
		</li>
		<li style="float: right;">
			<div class="uiaccountstyle">
				<form action="location_update.php" method="post">
					<h2><p>Country </p></h2></br>
					<p>
						<?php 
							echo 'You are from '. $db_country .'.';
						?>
					</p>
					<input type="text" name="country" id="country" class="placeholder" size="43" value="<?php echo $db_country; ?>"></br></br>
					<h2><p>Current City and Hometown</p></h2></br>
				Current City: </br><input type="text" name="city" id="city" class="placeholder" size="43" value="<?php echo $db_city; ?>"> </br></br>
					Hometown: </br><input type="text" name="hometown" id="hometown" class="placeholder" size="43" value="<?php echo $db_hometown; ?>"> </br></br>
					<input type="submit" name="send" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
					<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
		</li>
	</ul>
</div>
</div>
</body>
</html>