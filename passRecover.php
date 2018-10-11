<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
ob_start();
session_start();
if (isset($_SESSION['user_login'])) {
	header('location: signin.php');
}


$error = "";
//question update
//password variable
$username = strip_tags(@$_POST['username']);
$username = mysql_real_escape_string($username);

//Check whether the user has uploaded a profile pic or not
$check_pic = mysql_query("SELECT profile_pic, first_name, username FROM users WHERE username='$username' || email='$username'");
$get_pic_row = mysql_fetch_assoc($check_pic);
$profile_pic_db = $get_pic_row['profile_pic'];
$pic_uname = $get_pic_row['username'];
$fullname_db = $get_pic_row['first_name'];
//check for propic delete
$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$pic_uname' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
$get_pro_changed = mysql_fetch_assoc($pro_changed);
$pro_num = mysql_num_rows($pro_changed);
if ($pro_num == 0) {
	$profile_pic = "img/default_propic.png";
}else {
	$pro_changed_db = $get_pro_changed['photos'];
	if ($pro_changed_db != $profile_pic_db) {
		$profile_pic = "img/default_propic.png";
	}else {
		$profile_pic = "userdata/profile_pics/".$profile_pic_db;
	}
}

//update pass
if (isset($_POST['searchId'])) {
	//if the information submited
	$username_query = mysql_query("SELECT * FROM users WHERE username='$username' || email='$username'");
	$username_query_num = mysql_num_rows($username_query);
	if ($username_query_num >= 1) {
		$username_fetch_query = mysql_fetch_assoc($username_query);
		$get_username_fetch_query = $username_fetch_query['username'];
		$get_email_fetch_query = $username_fetch_query['email'];
		$get_active_fetch_query = $username_fetch_query['activated'];
		$get_block_fetch_query = $username_fetch_query['blocked_user'];
		if ($get_block_fetch_query == 1 ) {
			$error = "<p class='error_echo'>This account is blocked!</p>";
		}else if ($username == $get_username_fetch_query) {
			$_SESSION['username'] = $username;
			$error = "<p class='succes_echo'>Verify your personal information</p>";
			
			$succes_echoEmail = "
				<div>
					<img src=".$profile_pic." style='height: 115px;  width: 115px; border: 1px solid #ddd;'/>
				</div>
				<form action='passRecover.php' method='POST'>
					<span style='font-size:  margin: 13px 0 0 13px;  font-weight: 800; color: #088A08'>".$fullname_db."</span></br>
					<span style='font-size:  margin-left: 13px;  font-weight: 500; color: #575454'>@".$username."</span></br></br>
						Please enter your email to send code:</br></br><input type='email' name='recovEmail' class='placeholder' size='30' required autofocus></br></br>
					</hr>
					<input class='submRecov' type='submit' name='searchRecovEmail' id='senddata' value='Continue'>
				</form>
			";

		}else if ($username == $get_email_fetch_query) {
			$error = "<p class='succes_echo'>How do you want to reset your password?</p>";
			$_SESSION['con_email'] = $username;
			$_SESSION['con_uname'] = $get_username_fetch_query;
			$success_msg = "
				<p style='color: #black; font-size: 18px;'>We found the following information associated with your account.</p></br></br>
				<div>
					<img src=".$profile_pic." style='height: 115px;  width: 115px; border: 1px solid #ddd;'/>
				</div>
				<span style='font-size:  margin: 13px 0 0 13px;  font-weight: 800; color: #088A08'>".$fullname_db."</span></br>
				<span style='font-size:  margin-left: 13px;  font-weight: 500; color: #575454'>@".$get_username_fetch_query."</span></br></br>
				<form action='passRecover.php' method='POST'>
					<p style='color: #4C9ED9; font-size: 14px;'><input type='radio' checked> Email confirmation code to: ".$username." </p></br></br>
					<input class='submRecov' type='submit' name='searchRecovEmail2' id='senddata' value='Continue'>
				</form>
			";
		}else {
				$error = "<p class='error_echo'>This account is not activated!</p>";
			}
	}else {
		$error = "<p class='error_echo'>We couldn't find your account with that information!</p>";
	}
}

//checking email
$recovEmail = strip_tags(@$_POST['recovEmail']);
$recovEmail = mysql_real_escape_string($recovEmail);
if (isset($_POST['searchRecovEmail'])) {
	$username = $_SESSION['username'];
	$searchRecovEmail_query = mysql_query("SELECT * FROM users WHERE (email='$recovEmail' AND username='$username')");
	$searchRecovEmail_query_num = mysql_num_rows($searchRecovEmail_query);
	$usernm_fetch_query = mysql_fetch_assoc($searchRecovEmail_query);
	$get_first_nm_fetch_query = $usernm_fetch_query['first_name'];
	if ($searchRecovEmail_query_num >= 1) {
		$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
		$confirmCodeQuery = "UPDATE users SET confirmCode='$confirmCode' WHERE (username='$username' AND email='$recovEmail')";
		if (mysql_query($confirmCodeQuery)) {
			$_SESSION['final_uname'] = $username;
			$error = "<p class='succes_echo'>Verification code send to your email.</p>";
			// send email
			$msg = "Assalamu Alaikum  ".$get_first_nm_fetch_query."
			
			Somebody recently asked you to reset your password. 
			
			Your password reset code: ".$confirmCode." 
			
			N.B. If it's not by you, delete this mail or forget it.
			";
			//mail($recovEmail,"Daowat Confirmation Code",$msg, "From:Daowat <no-reply@daowat.com>");
			$success_email_cnfrm = "
				<form action='passRecover.php' method='POST'>
						Verification Code:</br></br><input type='text' name='confirmMailCode' class='placeholder' size='30' required autofocus></br></br>
					</hr>
						New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required></br></br>
					</hr>
						Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
					</hr>
					<input class='submRessetp' type='submit' name='confrmRessetpass' id='senddata' value='Reset Password'>
				</form>
			";
		}
	}else {
		$error = "<p class='error_echo'>Email don't match with this user!</p>";
	}
}

//checking email2

if (isset($_POST['searchRecovEmail2'])) {
	$user_con_email = $_SESSION['con_email'];
	$user_con_name = $_SESSION['con_uname'];
	$searchRecovEmail_query = mysql_query("SELECT * FROM users WHERE (email='$user_con_email' AND username='$user_con_name')");
	$searchRecovEmail_query_num = mysql_num_rows($searchRecovEmail_query);
	$usernm_fetch_query = mysql_fetch_assoc($searchRecovEmail_query);
	$get_first_nm_fetch_query = $usernm_fetch_query['first_name'];
	if ($searchRecovEmail_query_num >= 1) {
		$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
		$confirmCodeQuery = "UPDATE users SET confirmCode='$confirmCode' WHERE (email='$user_con_email' AND username='$user_con_name')";
		if (mysql_query($confirmCodeQuery)) {
			$_SESSION['final_uname'] = $user_con_name;
			$error = "<p class='succes_echo'>Verification code send to your email.</p>";
			// send email
			$msg = "Assalamu Alaikum  ".$get_first_nm_fetch_query."
			Somebody recently asked you to reset your password. 
			
			Your password reset code: ".$confirmCode."
			
			N.B. If it's not by you, delete this mail or forget it.
			";
			mail($user_con_email,"Daowat Confirmation Code",$msg, "From:Daowat <no-reply@daowat.com>");
			$success_email_cnfrm = "
				<form action='passRecover.php' method='POST'>
						Verification Code:</br></br><input type='text' name='confirmMailCode' class='placeholder' size='30' required autofocus></br></br>
					</hr>
						New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required></br></br>
					</hr>
						Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
					</hr>
					<input class='submRessetp' type='submit' name='confrmRessetpass' id='senddata' value='Reset Password'>
				</form>
			";
		}
	}else {
		$error = "<p class='error_echo'>Email don't match with this user!</p>";
	}
}

//confirm reseting password
$confirmMailCode = strip_tags(@$_POST['confirmMailCode']);
$newpassword = strip_tags(@$_POST['newpassword']);
$repear_password = strip_tags(@$_POST['newpassword2']);
$confirmMailCode = mysql_real_escape_string($confirmMailCode);
$newpassword = mysql_real_escape_string($newpassword);
$repear_password = mysql_real_escape_string($repear_password);
if (isset($_POST['confrmRessetpass'])) {
	$unameCnfrm = $_SESSION['final_uname'];
	if ($unameCnfrm != '') {
		$unameCnfrm_query = mysql_query("SELECT * FROM users WHERE username='$unameCnfrm'");
		$unameCnfrm_query_num = mysql_num_rows($unameCnfrm_query);
		if ($unameCnfrm_query_num >= 1) {
			$unameCnfrm_fetch_query = mysql_fetch_assoc($unameCnfrm_query);
			$get_uName_fetch_query = $unameCnfrm_fetch_query['username'];
			$get_confirmCode_fetch_query = $unameCnfrm_fetch_query['confirmCode'];
			$get_uEmail_fetch_query = $unameCnfrm_fetch_query['email'];
			if ($confirmMailCode == $get_confirmCode_fetch_query) {
				if ($newpassword == $repear_password) {
					$newpassword = md5($newpassword);
					$updatePassQuery = "UPDATE users SET password='$newpassword', activated='1' WHERE (username='$unameCnfrm')";
					if (mysql_query($updatePassQuery)) {
						$error = "<p class='succes_echo'>Password successfully changed.</p>";
						// send email
						$msg = "Successfully your Daowat password has been changed. 
						
						If it's not by you, please follow the link.
				
						http://www.daowat.com/confirmationPass.php?u=".$get_uName_fetch_query."&code=".$get_confirmCode_fetch_query ."
						
						";
						//mail($get_uEmail_fetch_query ,"Daowat Password Changed",$msg, "From:Daowat <no-reply@daowat.com>");
					}
				}else {
					$error = "<p class='error_echo'>Password don't match!</p>";
					$success_email_cnfrm = "
						<form action='passRecover.php' method='POST'>
								Verification Code:</br></br><input type='text' name='confirmMailCode' class='placeholder' size='30' required autofocus></br></br>
							</hr>
								New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required></br></br>
							</hr>
								Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
							</hr>
							<input class='submRessetp' type='submit' name='confrmRessetpass' id='senddata' value='Reset Password'>
						</form>
					";
				}
			}else {
				$error = "<p class='error_echo'>Incorect verification code!</p>";
				$success_email_cnfrm = "
					<form action='passRecover.php' method='POST'>
							Verification Code:</br></br><input type='text' name='confirmMailCode' class='placeholder' size='30' required autofocus></br></br>
						</hr>
							New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required></br></br>
						</hr>
							Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
						</hr>
						<input class='submRessetp' type='submit' name='confrmRessetpass' id='senddata' value='Reset Password'>
					</form>
				";
			}
		}
	}else {
		header('Location: passRecover.php');
	}
}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Password Recover</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<meta charset="uft-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>

<div>
	<div>
		<div class="headerLogin">
			<div class="login_menubar clearfix">
				<div class="menu_logo">
					<h1>
						<a title="Go to Daowat Home" href="index.php">
							<b>daowat</b>
						</a>
					</h1>
				</div>
				<div class="menu_login_container">
					<form action="index.php" method="POST">
						<table class="menu_login_container">
							<tr class="login_">
								<td>
								<?php
									if (isset($user)) {
									echo '<input type="submit" name="login" class="uiloginbutton" style="margin-top: 17px;" value="Back to home">';
								}else {
									echo '
									<input type="submit" name="login" class="uiloginbutton" style="margin-top: 17px;" value="Log In / Sign Up">
									';
									
									}
								?>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	</br></br>
	<div class="pass_body1" style="min-width: 900px;">
			<?php echo $error; ?>
		<div class="pass_body2">
				<?php
					if (isset($success_msg)) {
						echo $success_msg;
					}else if (isset($succes_echoEmail)) {
						echo $succes_echoEmail;
					}else if (isset($success_email_cnfrm)) {
						echo $success_email_cnfrm;
					}else {
						echo '
							<form action="passRecover.php" method="POST">
								<p>Find your Daowat account!</p></br>
									Enter email or username:</br></br><input type="text" name="username" class="placeholder" size="30" required autofocus></br></br>
								</hr></br>
								<input class="submRecov" type="submit" name="searchId" id="senddata" value="Search">
							</form>
						';
					}
				?>
			
		</div>
	</div>
	<div><?php include ( "./inc/footer.inc.php"); ?></div>
</div>

</body>
</html>
