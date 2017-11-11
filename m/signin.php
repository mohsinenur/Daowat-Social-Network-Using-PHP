<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
session_start();
if (isset($_COOKIE['user_login'])) {
	$_SESSION['user_login'] = $_COOKIE['user_login'];
	header("location: index.php");
	exit();
}

//


?>

<?php

//login box

$login_echo = "
	<form action='' method='POST'>
		<input class='login_unm_pss log_pass1' placeholder='Email address or username' id='email' type='text' name='user_login' size='30' required></br>
		<input class='login_unm_pss log_pass2' placeholder='Password' id='pass' type='password' name='password_login' size='30' required></br>
		<input class='loginButton' type='submit' name='login' value='Login'>
	</form>
	<form action='' method='POST'>
		<input class='submRessetp' type='submit' name='signin' id='goto_signUp' value='Create an Account'>
	</form>
	<div class='lo_forg_pss'>
		<a href='passRecover.php'>
			<span>forget password?</span>
		</a>
	</div>
   ";


//name check
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
			throw new Exception('Fullname can not be empty!');
			
		}
		if (is_numeric($_POST['first_name'][0])) {
			throw new Exception('Please write your correct name!');

		}
		if(empty($_POST['username'])) {
			throw new Exception('Username can not be empty!');
			
		}
		if (is_numeric($_POST['username'][0])) {
			throw new Exception('Username first character must be a letter!');

		}
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty!');
			
		}
		if(empty($_POST['password'])) {
			throw new Exception('Password can not be empty!');
			
		}
		if(empty($_POST['gender'])) {
			throw new Exception('Choose your gender!');
			
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
						if (@mail($_POST['email'],"Daowat Activation Code",$msg, "From:Daowat <no-reply@daowat.com>")) {
							
						$result = mysql_query("INSERT INTO users (first_name,username,email,password,gender,sign_up_date,confirmCode) VALUES ('$_POST[first_name]','$_POST[username]','$_POST[email]','$_POST[password]','$_POST[gender]','$d','$confirmCode')");
						$_SESSION['user_loginn'] = $_POST['username'];
						
						//follow requ
						$user_from = $_POST['username'];
						$user_to = 'nur';
						$create_followMe = mysql_query("INSERT INTO follow VALUES ('', '$user_from', '$user_to', NOW(), 'no')");
						$create_followFrom = mysql_query("INSERT INTO follow VALUES ('', '$user_to', '$user_from', NOW(), 'no')");
						//send message
						$msg_body = 'Assalamu Alaikum';
						$msgdate = date("Y-m-d");
						$opened = "no";
						$messagesuser = mysql_query("INSERT INTO pvt_messages VALUES ('','$user_to','$user_from','$msg_body','$msgdate','NOW()', '$opened','')");
						
						//success message
						$success_echo = 'Registration successfull!';
						$success_message = '
						<div style="text-align: center;font-size: 23px;font-weight: bold;color: #FFF;">
						<font face="bookman">You can login with usename or email. <br>
							Email: '.$u_email.'<br>
							Username: '.$_POST['username'].'
						</font></div>';
						}else {
							throw new Exception('Email is not valid!');
						}
						
						
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
		$error_echo = $e->getMessage();
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta charset="uft-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="">
	    <meta name="author" content="">
	    <script>
		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/IEMobile/i.test(navigator.userAgent)){
		if(document.URL !="http://m.daowat.com")
		{
		window.location ="http://m.daowat.com";
		}
		}
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
<body>
<div>
<div><?php include ( "./inc/header.inc.php" ); ?></div>
<div class="lo_bd_a">
<?php 
	if (isset($error_echo)) {
	echo '<p class="error_echo">'.$error_echo.'</p>';
	}
	if (isset($success_echo)) {
	echo '<p class="succes_echo">'.$success_echo.'</p>';
	}
?>
<div class="lo_bd_b">
<?php 
if (isset($success_message)) {
	echo $success_message;}
	?>
<h2>Sign Up!</h2>
<form action="" method="POST" class="registration">
	<div class="signup_form">
		<div>
			<td >
				<input name="first_name" id="first_name" placeholder="Full Name" required="required" class="signFld1 login_unm_pss signupbox" type="text" size="30" value="" >
			</td>
		</div>
		<div>
			<td>
				<input name="username" id="username" placeholder="Username" required="required" onBlur="checkusername()" onkeyup="clean('username')" onkeydown="clean('username')" class="signFld2 signupbox login_unm_pss" type="text" size="30" value="" >
			</td>
			<td style=" margin: 10px; padding: 2px; background-color: white;">
			<p id="usernamestatus"></p>
			</td>
		</div>
		<div>
			<td>
				<input name="email" placeholder="Enter Your Email" required="required" class="signFld2 signupbox login_unm_pss" type="email" size="30" value="">
			</td>
		</div>
		<div>
			<td>
				<input name="password" id="password-1" required="required" placeholder="Enter New Password" class="signFld3 signupbox login_unm_pss" type="password" size="30" value="">
			</td>
		</div>
		<div class="gender">
			<td>
				<th>
					<input type="radio" name="gender" value="1" checked requred/><span>Male</span>
				</th>
				<th>
					<input type="radio" name="gender" value="2" requred /><span>Female</span>
				</th>
			</td>
		</div>
		<div>
			<input name="signup" class='submRessetp' type='submit' id='goto_signUp' value='Sign Me Up!'>
		</div>
		<div class="signup_error_msg">
<?php 
	if (isset($error_message)) {echo $error_message;}
?>
</div>
</div>
</form>
<div class="lo_forg_pss">
	<a href="login.php">
		<span>Already have an account? Login...</span>
	</a>
</div>
</div>
</div>
	<div><?php include ( "./inc/footer.inc.php"); ?></div>
</div>
</body>
</html>