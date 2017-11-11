<?php include ( "./inc/connect.inc.php" ); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}

$username ="";
$firstname ="";
if (isset($_GET['u'])) {
	$username = mysql_real_escape_string($_GET['u']);
	if (ctype_alnum($username)) {
		//check user exists
		$check = mysql_query("SELECT username, first_name FROM users WHERE username='$username'");
		if (mysql_num_rows($check)===1) {
			$get = mysql_fetch_assoc($check);
			$username = $get['username'];
		}
		else {
			$username = $user;
		}
	}
}else {
	$username = $user;
}

$get_title_info = mysql_query("SELECT * FROM users WHERE username='$username'");
$get_title_fname = mysql_fetch_assoc($get_title_info);
$title_fname = $get_title_fname['first_name'];

$photoLocat = 'http://www.daowat.com';

include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

?>

 <?php 
	//daowat update and file check
	
	if (isset($_POST['subpost'])) {
		$error = "";
		$post= ($_POST['post']);
		$post = trim($post);
		$post= mysql_real_escape_string($post); 
		if ($post== "") {
		$error= "<p class='error_echo'>Please write your post!</p>";
		}else {
		$user_posted_to = $username;
		$date_added = date("Y-m-d");
		$added_by = $user;
		$sqlCommand = mysql_query("INSERT INTO posts(body,date_added,added_by,user_posted_to) VALUES('$post', '$date_added','$added_by', '$user_posted_to')");
		header("Location: newsfeed.php");
		}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Daowat</title>
	<meta charset="utf-8" />
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>
		<?php echo $error; ?>
		<div class="post_field" style="padding: 10px;">
		<table style="width: 100%;">
			<tbody>
			<?php
				echo'<form action="" method="POST">
					<tr style="margin-right: 10px;">
					<td>
						<div class="">
							<textarea name="post" class="WpostForm_text" ></textarea>

						</div>
					</td>
					</tr>
					<tr>
						
						<td>
							<input type="submit" name="subpost" value="Post" class="WpostSubmit" >
						</td>
					</tr>
				</form>';
				?>
			</tbody>
		</table>
		</div>

</div>
</body>
</html>