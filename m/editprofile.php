<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}


include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

?>

<?php
//take the user back

if ($user) {
	if (isset($_POST['no'])) {
		header('Location: about.php?u='.$user.'');
	}
}
else {
	die("You must be logged in to view this page!");
}

?>



<?php 
//work education update
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

if ($updateinfo) {
	$company = strip_tags(@$_POST['company']);
	$company = trim($company);
	$company = ucwords($company);
	$position = strip_tags(@$_POST['position']);
	$position = trim($position);
	$position = ucwords($position);
	//submit the form to database
	$info_submit_query = mysql_query("UPDATE users SET company='$company', position='$position' WHERE username='$user'");

	$school = strip_tags(@$_POST['school']);
	$school = trim($school);
	$school = ucwords($school);
	$concentration = strip_tags(@$_POST['concentration']);
	$concentration = trim($concentration);
	$concentration = ucwords($concentration);

	//submit the form to database
	$info_submit_query = mysql_query("UPDATE users SET school='$school', concentration='$concentration' WHERE username='$user'");
	echo "<p class='error_echo'>Your Profile Information Has Been Updated.</p>";
	header("Location: about.php?u=".$user."");
}

//email and  mobile
//Update Bio and first name last name query
$get_info = mysql_query("SELECT mobile,pub_email,email FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_mobile = $get_row['mobile'];
$db_pub_email = $get_row['pub_email'];
$db_email = $get_row['email'];


//submit what the user type in database

if ($sendeditcontact) {
	$mobile = strip_tags(@$_POST['mobile']);
	$mobile = trim($mobile);
	$mobile = mysql_real_escape_string($mobile);
	$pub_email = strip_tags(@$_POST['pub_email']);
	$pub_email = trim($pub_email);
	$pub_email = mysql_real_escape_string($pub_email);
	//submit the form to database
	$info_submit_query = mysql_query("UPDATE users SET mobile='$mobile',pub_email='$pub_email' WHERE username='$user'");
	header("Location: about.php?u=".$user."");
	}

?>

<?php

$error = "";
$sendeditlocation= @$_POST['sendeditlocation'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT country,city,hometown FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_country = $get_row['country'];
$db_city = $get_row['city'];
$db_hometown = $get_row['hometown'];

//submit what the user type in database

if ($sendeditlocation) {
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
	header("Location: about.php?u=".$user."");
	}
?>

<?php 

$updatebio= @$_POST['updatebio'];
$update = @$_POST['update'];
//Update Bio and first name last name query
$get_info = mysql_query("SELECT bio,queote FROM users WHERE username='$user'");
$get_row = mysql_fetch_assoc($get_info);
$db_bio = $get_row['bio'];
$db_queote = $get_row['queote'];

//submit what the user type in database
if ($updatebio) {
	$bio = $_POST['bio'];
	$bio = trim($bio);
	$bio = mysql_real_escape_string($bio);
	//submit the form to database
	$info_submit_query = mysql_query("UPDATE users SET bio='$bio' WHERE username='$user'");
	echo "<p class='error_echo'>Your Profile Bio Has Been Updated.</p>";
	header("Location: about.php?u=".$user."");
	}
if ($update) {
	$queote = $_POST['queote'];
	$queote = trim($queote);
	$queote = mysql_real_escape_string($queote);
	//submit the form to database
	$info_submit_query = mysql_query("UPDATE users SET queote='$queote' WHERE username='$user'");
	echo "<p class='error_echo'>Your Profile Queote Has Been Updated.</p>";
	header("Location: about.php?u=".$user."");
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Profile Edit</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>
		
		<?php
			if (isset($_GET['edit'])) {
				if ($_GET['edit'] == NULL) {
					header("location: newsfeed.php");
				}else if ($_GET['edit'] == "work_education") {
				echo'
				<div class="accountstyle ">
				<form action="" method="post">
				<h2><p>Update Work: </p></h2></br>
				Company: </br><input type="text" name="company" id="company" class="placeholder" value="'.$db_company.'"> </br></br>
				Position: </br><input type="text" name="position" id="position" class="placeholder"  value="'.$db_position.'"> </br></br>
				
				<h2><p>Update Education: </p></h2></br>
				School: </br><input type="text" name="school" id="school" class="placeholder"  value="'.$db_school.'"> </br></br>
				Subject: </br><input type="text" name="concentration" id="concentration" class="placeholder"  value="'.$db_concentration.'"> </br></br>
				<input type="submit" name="updateinfo" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
				</div>
					
				';
				}else if ($_GET['edit'] == "editcontact") {
				echo'
				<div class="accountstyle">
				<form action="" method="post">
				<h2><p>Public Email and Phone</p></h2></br>
				Email: </br><input type="email" name="pub_email" class="placeholder" value="'.$db_pub_email.'"> </br></br>
				Mobile: </br><input type="text" name="mobile" class="placeholder" value="'.$db_mobile.'"> </br></br>
				<input type="submit" name="sendeditcontact" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
				</div>
				';
				}else if ($_GET['edit'] == "editlocation") {
				echo'
				<div class="accountstyle">
				<form action="" method="post">
				<h2><p>Country </p></h2></br>
				<p>You are from '. $db_country .'</p>
				<input type="text" name="country" id="country" class="placeholder" value="'.$db_country.'"></br></br>
				<h2><p>Current City and Hometown</p></h2></br>
				Current City: </br><input type="text" name="city" id="city" class="placeholder" value="'.$db_city.'"> </br></br>
				Hometown: </br><input type="text" name="hometown" id="hometown" class="placeholder" value="'.$db_hometown.'"> </br></br>
				<input type="submit" name="sendeditlocation" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
				</div>
				';
				}else if ($_GET['edit'] == "editdetailsabout") {
				echo'
				<div class="accountstyle">
				<form action="" method="post">
				<h2><p>Describe Yourself: </p></h2></br>
				ABOUT YOU: </br>
				<textarea name="bio" id="aboutyou" class="placeholder" style="margin: 10px; padding: 5px; width: 90%; height: 140px; resize: none;"> '.$db_bio.' </textarea> </br>
				<input type="submit" name="updatebio" id="updatebio" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br></br>
				</form>
				</div>
				';
				}else if ($_GET['edit'] == "editqueote") {
				echo'
				<div class="accountstyle">
				<form action="" method="post">
				<h2><p>Update Your Favorite Queote: </p></h2></br>
				FAVORITE QUOTES: </br>
				<textarea name="queote" id="queote" class="placeholder" style="margin: 10px; padding: 5px; width: 90%; height: 140px; resize: none;">'.$db_queote.' </textarea> </br>
				<input type="submit" name="update" id="update" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
				</div>
				';
				}else {
				header('location: newsfeed.php');
				}
			}else {
				header('location: newsfeed.php');
			}
		?>
		
</div>
<div>
	<?php include("./inc/footer.inc.php") ?>
</div>
</body>
</html>