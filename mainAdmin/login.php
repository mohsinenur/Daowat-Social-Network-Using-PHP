<?php 
	include ( "./inc/connect.inc.php");
	if (isset($_POST['login'])) {
		if (isset($_POST['admin_user']) && isset($_POST['admin_pass'])) {
			$admin_user = mysql_real_escape_string($_POST['admin_user']);	
			$admin_pass = mysql_real_escape_string($_POST['admin_pass']);	
			$num = 0;
			$password_login_md5 = md5($admin_pass);
			$result = mysql_query("SELECT * FROM admin_login WHERE user_name='$admin_user' AND user_pass='$password_login_md5'");
			$num = mysql_num_rows($result);
			if ($num>0) {
				session_start();
				$_SESSION['admin_user'] = $admin_user;
				echo "<script>window.open('index.php','_self')</script>";
			}
			else {
				echo "<script>alert('Username or Password is incorrect!')</script>";
			}
		}

	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="adminStyle.css">
</head>
<body style="background-color: #9C9797;">
<form method="POST" action="login.php" style="padding: 84px 15px;">
	<table class="admintable" width="450px" border="10" align="center" bgcolor="#125612" style="margin: 0 auto;">
		<tr>
			<td class="login_header" colspan="4" align="center"><h1>Admin Login Form</h1></td>
		</tr>
		<tr  class="login_body">
			<td>User Name:</td>
			<td><input type="text" name="admin_user" /></td>
		</tr>
		<tr class="login_body">
			<td>User Password:</td>
			<td><input type="password" name="admin_pass" /></td>
		</tr>
		<tr class="login_footer">
			<td colspan="4" align="center"><input type="submit" name="login" value="Login" /></td>
		</tr>
	</table>
</form>
</body>
</html>