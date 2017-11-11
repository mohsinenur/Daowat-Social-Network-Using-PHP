<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
session_start();
if (isset($_COOKIE['user_login'])) {
	$_SESSION['user_login'] = $_COOKIE['user_login'];
	header("location: index.php");
	exit();
}

//defining variable
$error = "";
$rememberme = "";


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
   
//confirmation code and active account
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
		$error = "<p class='error_echo'>Incorect activation code</p>";
		$success_message = '
			<div style="text-align: center;font-size: 23px;font-weight: bold;color: #FFF; margin: 20px 0 0  0; line-height: 1.3;">
			<form action="login.php" method="POST">
					Enter activation code<input type="text" name="confrmCode" style="margin: 20px 0;" class="signFld2 login_unm_pss" size="30" required autofocus></br>
				
				<input class="submRessetp" type="submit" name="submconfrmCode" id="senddata" value="Continue Daowat"></br></br>
			</form>
			</div>';
	}
}

//login check

if (isset($_POST['login'])) {
		if (isset($_POST['user_login']) && isset($_POST['password_login'])) {
			$user_login = mysql_real_escape_string($_POST['user_login']);
			$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
			$password_login = mysql_real_escape_string($_POST['password_login']);
			//$rememberme = $_POST['rememberme'];		
			$num = 0;
			$password_login_md5 = md5($password_login);
			$result = mysql_query("SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND activated='1' AND blocked_user='0'");
			$num = mysql_num_rows($result);
			$get_user_email = mysql_fetch_assoc($result);
				$get_user_uname_db = $get_user_email['username'];
			if ($num>0) {
				$_SESSION['user_login'] = $get_user_uname_db;
				setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
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
					$error = "<p class='succes_echo'>Account activation code send to you.</p>";
					$success_message = '
						<div style="text-align: center;font-size: 23px;font-weight: bold;color: #FFF; margin: 20px 0 0 0; line-height: 1.3;">
						<font face="bookman">
							Please check your email: '.$get_user_email_db.'
						</font>
						<form action="login.php" method="POST">
								Enter activation code:<input type="text" style="margin: 20px 0;" name="confrmCode" class="signFld2 login_unm_pss" size="30" required autofocus></br>
							
							<input class="submRessetp" type="submit" name="submconfrmCode" id="senddata" value="Continue Daowat"></br></br>
						</form>
						</div>
						';
						//header('location: signin.php');
				}else {
					$result1 = mysql_query("SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND blocked_user='1'");
					$num1 = mysql_num_rows($result1);
					if ($num1>=1) {
					$error = "<p class='error_echo'>Opps!!! This account has been blocked.</p>";
						$success_message = $login_echo ;
					}else {
						$error = "<p class='error_echo'>Username or Password incorrect.</p>";
						$success_message = $login_echo ;
					}
					//header('location: signin.php');
				}
				
			}
		}

	}

if (isset($_POST['signin'])) {
	header('location: signin.php');
}


?>




<!DOCTYPE html>
<html>
<head>
	<title>Connecting Muslim Brother</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div>
	<?php include ( "./inc/header.inc.php" ); ?>
	<div class="lo_bd_a">
		<div>
			<?php echo $error; ?>
		</div>
		<div class="lo_bd_b">
			<?php 
			if (isset($success_message)) {
				echo $success_message;
			}else {
				echo $login_echo;
			    }
			?>
		</div>
	</div>
	<?php include ( "./inc/footer.inc.php"); ?>
</div>
</body>
</html>