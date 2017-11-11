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
	<title>Password Settings</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>

<?php include ( "./inc/header.inc.php"); ?>

<?php 

//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header("Location: password_update.php");
	}
}

//password update
$senddata = @$_POST['senddata'];
//password variable
$oldpassword = strip_tags(@$_POST['oldpassword']);
$newpassword = strip_tags(@$_POST['newpassword']);
$repear_password = strip_tags(@$_POST['newpassword2']);
$oldpassword = trim($oldpassword);
$newpassword = trim($newpassword);
$repear_password = trim($repear_password);

//update pass
if ($senddata) {
	//if the information submited
	$password_query = mysql_query("SELECT * FROM users WHERE username='$user'");
	while ($row = mysql_fetch_assoc($password_query)) {
		$db_password = $row['password'];
		$db_email = $row['email'];
		$db_first_name = $row['first_name'];
		//try to change MD5 pass
		$oldpassword_md5 = md5($oldpassword);
		if ($oldpassword_md5 == $db_password) {
			if ($newpassword == $repear_password) {
				//Awesome.. Password match.
				$newpassword_md5 = md5($newpassword);
				if (strlen($newpassword) <= 3) {
					$error = "<p class='error_echo'>Sorry! But your new password must be 3 or more then 5 character!</p>";
				}else {
				$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
				$password_update_query = mysql_query("UPDATE users SET password='$newpassword_md5', confirmCode='$confirmCode' WHERE username='$user'");
				$error = "<p class='succes_echo'>Success! Your password updated.</p>";
				// send email
				$msg = "Assalamu Alaikum  ".$db_first_name."
				
				Successfully your Daowat password has been changed. 
						
						If it's not by you, please follow the link.
				
						http://www.daowat.com/confirmationPass.php?u=".$user."&code=".$confirmCode."
						
						";
						mail($db_email ,"Daowat Password Changed",$msg, "From:Daowat <no-reply@daowat.com>");
				}
			}else {
				$error = "<p class='error_echo'>Two new password don't match!</p>";
			}

		}else {
			$error = "<p class='error_echo'>The old password is incorrect!</p>";
		}
	}
}else {
	$error = "";
}

?>
<div style="margin-top: 48px;">
<?php echo $error; ?>
<div style="width: 900px; margin: 0 auto;">
	<ul>
		<li style="float: left;">
			<div class="settingsleftcontent">
				<ul>
					<li><a href="profile_update.php">Profile Update</a></li>
					<li><a href="account_update.php">Account</a></li>
					<li><a href="password_update.php" style=" background-color: #0B810B; border-radius: 3px; color: #fff;">Password</a></li>
					<li><a href="workedu_update.php">Work and Education</a></li>
					<li><a href="cbinfo_update.php">Contact and Basic Info</a></li>
					<li><a href="location_update.php">Location and Places</a></li>
					<li style="border-bottom: none;"><a href="details_update.php">Details About</a></li>
				</ul>
			</div>
			<div class="settingsleftcontent" style="background-color: #fff;">
				<?php include './inc/profilefooter.inc.php'; ?>
			</div>
		</li>
		<li style="float: right;">
		<form action="password_update.php" method="post">
			<div class="uiaccountstyle">
				<h2><p>Change Your Password: </p></h2></br>
				Your Old Password: </br><input type="password" name="oldpassword" class="placeholder" size="30"></br></br>
				Your New Password: </br><input type="password" name="newpassword" class="placeholder" size="30"></br></br>
				Retype New Password: </br><input type="password" name="newpassword2" class="placeholder" size="30"></br>
				</hr></br>
				<input type="submit" name="senddata" id="senddata" class="confirmSubmit" value="Update Password">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</div>
			</form>
		</li>
	</ul>
</div>
</div>
</body>
</html>