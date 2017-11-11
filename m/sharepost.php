<?php 
include ( "./inc/connect.inc.php");

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

if (isset($_REQUEST['pid'])) {
	$id = mysql_real_escape_string($_REQUEST['pid']);
	//get info
	$get_file = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get_file_name = mysql_fetch_assoc($get_file);
	$db_username = $get_file_name['added_by'];
	$u_posted_to= $get_file_name['user_posted_to'];
	$sharepostid = $get_file_name['share_post'];
	if($sharepostid != 0) {
		$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$sharepostid'");
		$post_info = mysql_fetch_assoc($get_sharepost );
		$sp_id = $post_info['id'];
		$sp_body = $post_info['body'];
	}
	
	$db_post = $get_file_name['body'];
	if($u_posted_to != $db_username) {
		header('location: login.php');
	}
	
}else if (isset($_REQUEST['did'])) {
	$id = mysql_real_escape_string($_REQUEST['did']);
	//get info
	$get_file = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get_file_name = mysql_fetch_assoc($get_file);
	$db_username = $get_file_name['added_by'];
	$u_posted_to= $get_file_name['user_posted_to'];
	$sharedaowatid = $get_file_name['daowat_post'];
	if($sharedaowatid != 0) {
		$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$sharedaowatid'");
		$post_info = mysql_fetch_assoc($get_sharepost );
		$sp_id = $post_info['id'];
		$sp_body = $post_info['body'];
	}
	
	$db_post = $get_file_name['body'];
	if($u_posted_to != $db_username) {
		header('location: login.php');
	}
	
}else {
	header('location: index.php');
}

//getting user info
$check_user = mysql_query("SELECT * FROM users WHERE username='$user'");
$get_check_user_row = mysql_fetch_assoc($check_user );
$gender_user_db = $get_check_user_row ['gender'];



if(isset($_POST['cancel'])) {
	header('location: moreoptions.php?pid=$id');
}

//getting first name
$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$id'");
$post_info = mysql_fetch_assoc($get_sharepost );
$sp_added_by = $post_info['added_by'];
$sp_get_user_info = mysql_query("SELECT * FROM users WHERE username='$sp_added_by'");
$sp_get_info = mysql_fetch_assoc($sp_get_user_info);
$sp_profile_pic_db= $sp_get_info['profile_pic'];
$sp_add_by = $sp_get_info['first_name'];

//share post
$post = ($_POST['post']);
$post = trim($post);
$post = mysql_real_escape_string($post);

if(isset($_POST['share'])) {
	$user_posted_to = $user;
	$date_added = date("Y-m-d");
	$added_by = $user;
	
	$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to,share_post) VALUES('$post', '$date_added', '$added_by', '$user_posted_to', '$id')";
	$query = mysql_query($sqlCommand) or die (mysql_error());
	header("Location: newsfeed.php");
}else if(isset($_POST['daowat'])) {
	$user_posted_to = $user;
	$date_added = date("Y-m-d");
	$added_by = $user;
	
	$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to,daowat_post) VALUES('$post', '$date_added', '$added_by', '$user_posted_to', '$id')";
	$query = mysql_query($sqlCommand) or die (mysql_error());
	header("Location: index.php");
}else {

}

?>




<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		
		.c_b {
			color: black;
		}
		.cc {
			display: inline-block;
			margin: 6px 0 6px 6px;
		}
		.vp {
			text-decoration: none;
			color: #0b810b;
			font-size: 16px;
		}
	</style>
	<title>Edit Post</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="pro_body">
	<div class="pro_header">
		<nav class="pro_hdr_menu">
			<?php include ( "./inc/hdrmenu.inc.php"); ?>
		</nav>
	</div>
	<div style="width: 100%;">
	<?php
		if($db_username == $u_posted_to) {
			echo '
				
				<div style="width: 100%;">
					<form action="" method="POST">
					<div class="">
					<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" style="margin: 6px; padding: 5px; width: 90%; height: 140px;"  ></textarea>
		
					</div>
					<div>
					<table>
					<tbody>
						<tr>
							<td></td>
							<td>
								<tr>'.$db_username.'</tr>
								<tr>'.$db_post.'</tr>
							</td>
						</tr>
					</tbody>
					</table>
					</div>
					<div class="cc">';
					if (isset($_REQUEST['pid'])) {
						echo'<input name="share" value="Share" type="submit" class="confirmSubmit"></input>';
					}else if (isset($_REQUEST['did'])) {
						echo'<input name="daowat" value="Daowat" type="submit" class="confirmSubmit"></input>';
					}
					echo'</div>
					<div class="cc">
						<input name="cancel" value="Cancel" type="submit" class="cancelSubmit"></input>
					</div>
					<div class="cc">
						<a href="viewPost.php?pid='.$id.'" class="vp">Back to post</a>
					</div>
					</form>
				</div>

			';
		
		}else {
			header('location: newsfeed.php');
		}
	?>
	</div>

</div>

</body>
</html>	
	
	
	
	
	
	
	
	
	