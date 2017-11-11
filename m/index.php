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

$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$city = $geo["geoplugin_city"];
$region = $geo["geoplugin_regionName"];
$country = $geo["geoplugin_countryName"];


$photoLocat = 'http://www.daowat.com';

include ( "./inc/headerfmnoti.inc.php");
//update online tine
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

?>

<?php 
$username ="";
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
			die();
		}
	}
}

if (isset($_POST['newDaowat'])) {
	header("location: composedaowat.php");
}


//Check whether the user has uploaded a profile pic or not
$check_pic = mysql_query("SELECT profile_pic FROM users WHERE username='$user'");
$get_pic_row = mysql_fetch_assoc($check_pic);
$profile_pic_db = $get_pic_row['profile_pic'];
//check for userfrom propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_pic= $photoLocat."/img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db ) {
			$profile_pic= $photoLocat."/img/default_propic.png";
		}else {
			$profile_pic= $photoLocat."/userdata/profile_pics/".$profile_pic_db ;
		}
		}

//name query
$about_query = mysql_query("SELECT first_name FROM users WHERE username='$user'");
$get_result = mysql_fetch_assoc($about_query);
$first_name_user = $get_result['first_name'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Daowat</title>
	<meta charset="utf-8" />
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('body').on('keydown', '#post', function(e) {
		    console.log(this.value);
		    if (e.which === 32 &&  e.target.selectionStart === 0) {
		      return false;
		    }
		  });
		});
	</script>
	<style>
	.hdr_menu_btn {
	background: none; 
	cursor: pointer; 
	border: none;
	}
	</style>
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<ul>
					<form method="POST" action="">
					<li ><a href="index.php" style="color: #2AED25; font-weight: bold;">Daowat</a></li>	
					<li><a href="newsfeed.php">Newsfeed</a></li>
					<li><a href="profile.php?u=<?php echo "$user"; ?>">Profile</a></li>
					<?php 
						if($unread_numrows >= 1) 
						{
							echo "<li><a href='messages.php' style='color: yellow;'>Message(".$unread_msg_numrows.")</a></li>";
						}else {
							echo "<li><a href='messages.php'>Message</a></li>";
						}
						if($follow_numrows >= 1) 
						{
							echo "<li><a href='followRequest.php' style='color: yellow;'>Friends(".$unread_follow_numrows.")</a></li>";
						}else {
							echo "<li><a href='followRequest.php'>Friends</a></li>";
						}
						if($post_noti_num >= 1) 
						{
							echo "<li><a href='notification.php' style='color: yellow;'>Notification(".$post_noti_num.")</a></li>";
						}else {
							echo "<li><a href='notification.php'>Notification</a></li>";
						}
					 ?>
					<li><a href="search.php">Search</a></li>
					</form>
				</ul>
			</nav>
		</div>
		<div class="post_field">
				<div class="profilePosts">
					<div class="postForm">
						<form action="" method="POST">
							<div style="float: left; margin: 10px;">
								<?php  
									echo "<img src='$profile_pic' style='height: 35px; width: 35px; border: 1px solid rgb(221, 221, 221);' />";
									
								?>
							</div>
							<div style="float: right;">
								<input type="submit" name="newDaowat" value="New Daowat" class="postSubmit" >
							</div>
							
						</form>
					</div>
					<?php

					//timeline query table
					$lastid = "";
					$getposts = mysql_query("SELECT * FROM posts WHERE daowat_give != '0' ORDER BY id DESC LIMIT 10") or die(mysql_error());
					if (mysql_num_rows($getposts)) {
					echo '<ul id="recs">';
					while ($row = mysql_fetch_assoc($getposts)) {
						include ( "./inc/getDaowatpost.inc.php" );
						$lastid = $row['id'];
					}
					echo '<li class="getmore" id="'.$lastid.'" >Show More</li>';
					echo '</ul>';
					}
					echo '
			</div>
		</br>
</div>';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.getmore').live('click',function() {
				var lastid = $(this).attr('id');
				$.ajax({
					type: 'GET',
					url: 'showmorenext.php',
					data: 'lastid='+lastid,
					beforeSend: function() {
						$('.getmore').html('Loading....');
					},
					success: function(data) {
						$('.getmore').remove();
						$('#recs').append(data);
					}
				});
			});
		});
	</script>

</div>
<div>
			<?php include("./inc/footer.inc.php") ?>
		</div>
</body>
</html>