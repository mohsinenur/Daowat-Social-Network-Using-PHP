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
	$db_post = $get_file_name['body'];
	if($db_username != $user) {
		header('location: login.php');
	}
	
}else {
	header('location: index.php');
}





if(isset($_POST['cancel'])) {
	header('location: moreoptions.php?pid=$id');
}


$post = ($_POST['post']);
$post = trim($post);
$post = mysql_real_escape_string($post);

if ($post != "") {
	//submit the form to database
	$info_submit_query = mysql_query("UPDATE posts SET body='$post' WHERE id='$id'");
	echo "<p class='error_echo'>Your Profile Queote Has Been Updated.</p>";
	header("Location: viewPost.php?pid=$id");
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
		if($db_username == $user) {
			echo '
				
				<div style="width: 100%;">
					<form action="" method="POST">
					<div class="">
					<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" style="margin: 6px; padding: 5px; width: 90%; height: 140px;"  >'.$db_post.'</textarea>
		
					</div>
					<div class="cc">
						<input name="update" value="Update" type="submit" class="confirmSubmit"></input>
					</div>
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
	
	
	
	
	
	
	
	
	