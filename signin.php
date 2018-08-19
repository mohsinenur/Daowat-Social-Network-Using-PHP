<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
session_start();
if (isset($_COOKIE['user_login'])) {
	$_SESSION['user_login'] = $_COOKIE['user_login'];
	header("location: index.php");
	exit();
}

if (isset($_POST['login'])) {
		if (isset($_POST['user_login']) && isset($_POST['password_login'])) {
			$user_login = mysql_real_escape_string($_POST['user_login']);
			$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
			$password_login = mysql_real_escape_string($_POST['password_login']);
			$rememberme = $_POST['rememberme'];		
			$num = 0;
			$password_login_md5 = md5($password_login);
			$result = mysql_query("SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND activated='1' AND blocked_user='0'");
			$num = mysql_num_rows($result);
			$get_user_email = mysql_fetch_assoc($result);
				$get_user_uname_db = $get_user_email['username'];
			if ($num>0) {
				$_SESSION['user_login'] = $get_user_uname_db;
				if ($rememberme != NULL) {
					setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
				}
				header('location: index.php');
				exit();
			}
			else {
				$result1 = mysql_query("SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND activated ='0'");
				$num1 = mysql_num_rows($result1);
				$get_user_email = mysql_fetch_assoc($result1);
				$get_user_name_db = $get_user_email['username'];
				$get_user_email_db = $get_user_email['email'];
				$get_user_confrmCode_db = $get_user_email['confirmCode'];
				if ($num1>0) {
					$_SESSION['user_loginn'] = $get_user_name_db ;
					$_SESSION['user_confrmCode'] = $get_user_confrmCode_db;
					$success_message = '
						<div class="maincontent_text" style="text-align: center;">
						<font face="bookman">Account activation code send to you. <br>
							Please check your mail: '.$get_user_email_db.'
						</font>
						<form action="signin.php" method="POST">
								Enter varification code:<input type="text" name="confrmCode" class="submRecov" size="30" required></br>
							
							<input class="submRecov" type="submit" name="submconfrmCode" id="senddata" value="Continue Daowat">
						</form>
						</div>
						';
						//header('location: signin.php');
				}else {
					$result1 = mysql_query("SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND blocked_user='1'");
					$num1 = mysql_num_rows($result1);
					if ($num1>=1) {
						$success_message = '
						<h2><font face="bookman">Opps!!!</font></h2>
							<div class="maincontent_text" style="text-align: center;">
							<font face="bookman">This account has been blocked.<br>
							</font></div>';
					}else {
						$success_message = '
						<h2><font face="bookman">Sorry!!!</font></h2>
							<div class="maincontent_text" style="text-align: center;">
							<font face="bookman">Username or Password incorrect.<br>
							</font></div>';
					}
					//header('location: signin.php');
				}
				
			}
		}

	}

if (isset($_POST['submconfrmCode'])) {
	$user_confrmCode_db = mysql_real_escape_string($_POST['confrmCode']);
	$user_loginnn = $_SESSION['user_loginn'];
	$result2 = mysql_query("SELECT * FROM users WHERE username='$user_loginnn' AND confirmCode='$user_confrmCode_db' AND activated='0'");
	$num2 = mysql_num_rows($result2);
	$get_user_info_f = mysql_fetch_assoc($result2);
	if ($num2>=1) {
		$password_update_query = mysql_query("UPDATE users SET activated='1', confirmCode='0' WHERE username='$user_loginnn'");
		
		//creating session
		$_SESSION['user_login'] = $user_loginnn;
				
				setcookie('user_login', $user_loginnn, time() + (365 * 24 * 60 * 60), "/");
				
				header('location: index.php');
				exit();
		
	}else {
		$success_message = '
			<div class="maincontent_text" style="text-align: center;">
			<font face="bookman">Incorect varification code
			</font>
			<form action="signin.php" method="POST">
					Enter varification code:<input type="text" name="confrmCode" class="submRecov" size="30" required></br>
				
				<input class="submRecov" type="submit" name="submconfrmCode" id="senddata" value="Continue Daowat">
			</form>
			</div>';
	}
}

?>

<?php
if(isset($_POST["name2check"]) && $_POST["name2check"] != ""){
    $username = preg_replace('#[^a-z0-9]#i', '', $_POST['name2check']); 
    $sql_uname_check = mysql_query("SELECT id FROM users WHERE username='$username' LIMIT 1"); 
    $uname_check = mysql_num_rows($sql_uname_check);
    if (strlen($username) < 5 || strlen($username) > 15 ) {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">5 - 15 characters please</p>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">First character must be a letter</p>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<p style="color: #0B810B; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">Success! Remember username for login</p>';
	    exit();
    } else {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;"><strong>' . $username . '</strong> has taken! Choose another.</p>';
	    exit();
    }
}
?>

<?php

if (isset($_POST['signup'])) {
//declere veriable
$u_name = $_POST['username'];
$u_name  = trim($u_name);
$u_name  = strtolower($u_name);
$u_name  = preg_replace('/\s+/','',$u_name);
$u_email = $_POST['email'];
//triming name
$_POST['first_name'] = trim($_POST['first_name']);
$_POST['username'] = trim($_POST['username']);
$_POST['username'] = strtolower($_POST['username']);
$_POST['username'] = preg_replace('/\s+/','',$_POST['username']);
	try {
		if(empty($_POST['first_name'])) {
			throw new Exception('Fullname can not be empty');
			
		}
		if (is_numeric($_POST['first_name'][0])) {
			throw new Exception('Please write your correct name!');

		}
		if(empty($_POST['username'])) {
			throw new Exception('Username can not be empty');
			
		}
		if (is_numeric($_POST['username'][0])) {
			throw new Exception('Username first character must be a letter!');

		}
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty');
			
		}
		if(empty($_POST['password'])) {
			throw new Exception('Password can not be empty');
			
		}
		if(empty($_POST['gender'])) {
			throw new Exception('Gender can not be empty');
			
		}

		if (strlen($_POST['first_name']) <7 || strlen($_POST['first_name']) >20 )  {
			throw new Exception('Full name must be 8 to 20 characters!');
		}

		//username check
		$u_check = mysql_query("SELECT username FROM users WHERE username='$u_name'");
		$check = mysql_num_rows($u_check);
		// Check if email already exists
		$e_check = mysql_query("SELECT email FROM users WHERE email='$u_email'");
		$email_check = mysql_num_rows($e_check);
		if (strlen($_POST['username']) >4 && strlen($_POST['username']) <16 ) {
			if ($check == 0 ) {
				if ($email_check == 0) {
					if (strlen($_POST['password']) >4 ) {
						$d = date("Y-m-d"); //Year - Month - Day
						$_POST['first_name'] = ucwords($_POST['first_name']);
						$_POST['username'] = strtolower($_POST['username']);
						$_POST['username'] = preg_replace('/\s+/','',$_POST['username']);
						$_POST['password'] = md5($_POST['password']);
						$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
						// send email
						$msg = "
						Assalamu Alaikum... 
						
						Your activation code: ".$confirmCode."
						Username: ".$_POST['username']."
						Signup email: ".$_POST['email']."
						
						";
						//if (@mail($_POST['email'],"Daowat Activation Code",$msg, "From:Daowat <no-reply@daowat.com>")) {
							
						$result = mysql_query("INSERT INTO users (first_name,username,email,password,gender,sign_up_date,confirmCode) VALUES ('$_POST[first_name]','$_POST[username]','$_POST[email]','$_POST[password]','$_POST[gender]','$d','$confirmCode')");
						$_SESSION['user_loginn'] = $_POST['username'];
						
						//sent follow
						//$user_from = $_POST['username'];
						//$user_to = 'nur';
						//$create_followMe = mysql_query("INSERT INTO follow VALUES ('', '$user_from', '$user_to', NOW(), 'no')");
						//$create_followFrom = mysql_query("INSERT INTO follow VALUES ('', '$user_to', '$user_from', NOW(), 'no')");
						//send message
						//$msg_body = 'Assalamu Alaikum';
						//$msgdate = date("Y-m-d");
						//$opened = "no";
						//$messages = mysql_query("INSERT INTO pvt_messages VALUES ('','$user_to','$user_from','$msg_body','$msgdate','NOW()','$opened', '')");
						
						//success message
						$success_message = '
						<h2><font face="bookman">Registration successfull!</font></h2>
						<div class="maincontent_text" style="text-align: center;">
						<font face="bookman">You can login with usename or email. <br>
							Email: '.$u_email.'<br>
							Username: '.$_POST['username'].'
						</font></div>';
						//}else {
						//	throw new Exception('Email is not valid!');
						//}
						
						
					}else {
						throw new Exception('Password must be 5 or more then 5 characters!');
					}
				}else {
					throw new Exception('Email already taken!');
				}
			}else {
				throw new Exception('Username already taken!');
			}
		}else {
			throw new Exception('Username must be 5-15 characters!');
		}

	}
	catch(Exception $e) {
		$error_message = $e->getMessage();
	}
}

//getting daowat post photo
$getposts = mysql_query("SELECT * FROM daowat WHERE photos != '' ORDER BY  RAND() ");
$row = mysql_fetch_assoc($getposts);
$photos_db = $row['photos'];
$photosrow = "./userdata/daowat_pics/".$photos_db;


?>


<!doctype html>
<html>
	<head>
	<title>Connecting Muslim Brother</title>
	<meta charset="uft-8">
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="image_src" href="http://www.daowat.com/userdata/daowat_pics/nur/1453542261.jpg" /><!--formatted-->
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<meta name="description" content="Assalamu Alaikum. Join Daowat. Connect with your family. Daowat to muslim brother. Get update over the world." /><!--formatted-->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="author" content="Mohsin E Nur">
	    <script type="text/javascript">
		  <!--
		  if (screen.width <= 800) {
		    window.location = "http://m.daowat.com/login.php";
		  }
		  //-->
		</script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/hideShowPassword.js"></script>	
		<script>
			$(window).ready(function(){
				$('#password-1').hideShowPassword({
				  // Creates a wrapper and toggle element with minimal styles.
				  innerToggle: true,
				  // Makes the toggle functional in touch browsers without
				  // the element losing focus.
				  touchSupport: Modernizr.touch
				});
			});
		</script>
	    <style>
		  ::-ms-reveal {
		    display:none !important;
		  }
		  .hideShowPassword-toggle {
		    background-image: url(./img/wink.svg);
		    background-position: 0 center;
		    background-repeat: no-repeat;
		    cursor: pointer;
		    height: 100%;
		    overflow: hidden;
		    text-indent: -9999em;
		    width: 44px;
		  }
		  .hideShowPassword-toggle-hide {
		    background-position: -44px center;
		  }
		  .hideShowPassword-toggle,
		  .my-toggle-class {
		    z-index: 3;
		  }
		</style>
		
	    <script src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript">
			$(function() {
			  $('body').on('keydown', '#first_name', function(e) {
			    console.log(this.value);
			    if (e.which === 32 &&  e.target.selectionStart === 0) {
			      return false;
			    }  
			  });
			});
		</script>
		<script type="text/javascript" language="javascript">
		function checkusername(){
			var status = document.getElementById("usernamestatus");
			var u = document.getElementById("username").value;
			if(u != ""){
				status.innerHTML = 'checking...';
				var hr = new XMLHttpRequest();
				hr.open("POST", "signin.php", true);
				hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
						status.innerHTML = hr.responseText;
					}
				}
		        var v = "name2check="+u;
		        hr.send(v);
			}
		}
		</script>
		<script type="text/javascript">
			function clean (username) {
				var textfield = document.getElementById(username);
				var regex = /[^a-z0-9]/g;
				textfield.value = textfield.value.replace(regex, "");
			    }
		</script>
		
	</head>
	<body style= "background: url(<?php echo $photosrow; ?>) no-repeat center center; background-size: 100%; ">
		<div class="main">
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
						<form action="" method="POST">
							<table class="menu_login_container">
								<tr>
									<td class="logintd">
										<label for="email">Username or Email</label>
									</td>
									<td class="logintd">
										<label for="pass">Password</label>
									</td>
								</tr>
								<tr class="login_">
									<td>
									    <input type="text" name="user_login" id="email" required="required" value="" class="inputtext">
									</td>
									<td>
										<input type="password" name="password_login" required="required" id="pass" value="" class="inputtext">
									</td>
									<td>
										<input type="submit" name="login" class="uiloginbutton" value="Log In">
									</td>
								</tr>
								<tr>
									<td class="">
										<label>
											<div>
												<input class="logincheckbox uiInputLabelInput" name="rememberme" type="checkbox" checked>
												<div class="uiInputLabelLabel" style="color:#FFFFFF">Keep me logged in</div>
											</div>
										</label>
									</td>
									<td class="login_form_label_field">
										<a href="passRecover.php" style="text-decoration:none; color:#FFFFFF">Forgot your password?</a>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
			<div class="holecontainer">
				<div class="container">
					<div>
						<div>
							<div class="maincontent">
							   <?php
								if (isset($success_message)) {
									echo $success_message;
								}else {
									echo '
									   <h2><font face="bookman">Assalamu Alaikum!</font></h2>
										<div class="maincontent_text">
										<font face="bookman">Join Daowat.com <br>
											<li>Connect with your family.</li>
											<li>Daowat to muslim brother.</li>
											<li>Get update over the world.</li>
										</font>
										</div>
										<div class="uisignupbutton signupbutton" >
											<a title="Visit Daowat" href="visitDaowat.php" style="text-decoration: none; font-size: 27px; color: #fff;">
												<b>Visit Daowat</b>
											</a>
										</div>
									';
								}
							   ?>
								
							</div>
							<div class="signupform_content">
								<h2>Sign Up!</h2>
								<div class="signupform_text"></div>
								<div>
									<form action="" method="POST" class="registration">
										<div class="signup_form">
											<div>
												<td >
													<input name="first_name" id="first_name" placeholder="Full Name" required="required" class="first_name signupbox_wihei signupbox" type="text" size="30" value="" >
												</td>
											</div>
											<div>
												<td>
													<input name="username" id="username" placeholder="Username" required="required" onBlur="checkusername()" onkeyup="clean('username')" onkeydown="clean('username')" class="user_name signupbox signupbox_wihei" type="text" size="30" value="" >
												</td>
												<td style=" margin: 10px; padding: 2px; background-color: white;">
												<p id="usernamestatus"></p>
												</td>
											</div>
											<div>
												<td>
													<input name="email" placeholder="Enter Your Email" required="required" class="email signupbox signupbox_wihei" type="email" size="30" value="">
												</td>
											</div>
											<div>
												<td>
													<input name="password" id="password-1" required="required" style="overflow: hidden; padding-right: 7px;" placeholder="Enter New Password" class="password signupbox passbox_wihei" type="password" size="30" value="">
												</td>
											</div>
											<div class="gender">
												<td>
													<th>
														<div style="float: left;padding: 13px 13px 0 13px;font-size: 16px;font-weight: bold;">
															<input type="radio" name="gender" value="1" requred checked/><span>Male</span>
														</div>
													</th>
													<th>
														<div style="float: left;padding: 13px 13px 0 13px;font-size: 16px;font-weight: bold;">
															<input type="radio" name="gender" value="2" /><span>Female</span>
														</div>
													</th>
												</td>
											</div>
											<div>
												<input name="signup" class="uisignupbutton signupbutton" type="submit" value="Sign Me Up!">
											</div>
											<div class="signup_error_msg">
									<?php 
										if (isset($error_message)) {echo $error_message;}
									?>
									</div>
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php include ( "./inc/footer.inc.php"); ?>
