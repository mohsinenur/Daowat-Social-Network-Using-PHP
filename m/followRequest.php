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

include ( "./inc/headerfmnoti.inc.php");

//read follow request
$pstopened_query = mysql_query("UPDATE follow SET opened='yes' WHERE user_to='$user'");

//name query
$about_query = mysql_query("SELECT first_name FROM users WHERE username='$user'");
$get_result = mysql_fetch_assoc($about_query);
$first_name_user = $get_result['first_name'];

$folder_link = 'http://www.daowat.com/';

//update online tine
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
	
<!DOCTYPE html>
<html>
<head>
	<title>Daowat</title>
	<link rel="icon" href="".$folder_link."img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.notificationbox a {
			text-decoration: none;
			color: #0b810b;
		}
	</style>
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<ul>
					<li><a href="index.php">Daowat</a></li>
					<li><a href="newsfeed.php">Newsfeed</a></li>
					<li><a href='profile.php?u=".$user."'>Profile</a></li>
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
							echo "<li><a href='followRequest.php' style='color: #2AED25; font-weight: bold;' >Friends</a></li>";
						}
						if($post_noti_num >= 1) 
						{
							echo "<li><a href='notification.php' style='color: yellow;'>Notification(".$post_noti_num.")</a></li>";
						}else {
							echo "<li><a href='notification.php'>Notification</a></li>";
						}
					 ?>
					<li> <a href="search.php">Search</a></li>	
					
				</ul>
			</nav>
		</div>
		<h2 style='font-size: 20px; margin: 10px;'>Follow Requests</h2>
		 <div class="notificationbox">
		 	<div style='overflow: auto;'>
		 		<ul>
		 			<li style='line-height: 15px;'>
		 				<?php 
		 					//getting post comment
							$get_followR = mysql_query("SELECT * FROM follow WHERE user_to ='$user' ORDER BY id DESC LIMIT 50");
							$count_get_followR = mysql_num_rows($get_followR);
							$count = $count_get_followR;
							if ($count != 0) {
								//getting post noti
								while ($noti = mysql_fetch_assoc($get_followR)) {
									$time = $noti['time'];
									$user_from = $noti['user_from'];
									$get_user_info = mysql_query("SELECT * FROM users WHERE username='$user_from'");
									$get_info = mysql_fetch_assoc($get_user_info);
									$profile_pic_db= $get_info['profile_pic'];
									$user_from_fname = $get_info['first_name'];
									$comment_disp = "started following you.";
									
									//check for propic delete
									$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_from' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
									$get_pro_changed = mysql_fetch_assoc($pro_changed);
					$pro_num = mysql_num_rows($pro_changed);
					if ($pro_num == 0) {
						$profile_pic = "".$folder_link."img/default_propic.png";
					}else {
						$pro_changed_db = $get_pro_changed['photos'];
					if ($pro_changed_db != $profile_pic_db) {
						$profile_pic = "".$folder_link."img/default_propic.png";
					}else {
						$profile_pic = "".$folder_link."userdata/profile_pics/".$profile_pic_db;
					}
					}
														echo "<div style='margin: 10px; border-bottom: 1px solid #cdcdcd;' ><div style='margin: 10px 0px; padding: 0px;'>";
											echo "<div style=' float: left; margin: 0px 10px 0 0;'><img src='$profile_pic'  height='35' width='35'  /></div>";
										echo "<div style='margin: 0 0 0 50px;'>";
											echo "<b><a href='profile.php?u=$user_from' style='text-decoration: none; font-size: 15px; color: #0B810B;'  class='posted_by'>$user_from_fname</a></b><span style='font-size: 15px; margin-left: 10px;'>".$comment_disp."</span>
											"; 
												
											echo "<br>";
												echo "<span style='font-size: 10px; color: #7C7979;'>".$time."</span>
												";
										echo "</div>";
									echo "</div></div>";
								}
							}else {
								echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br><br>You have no follow request!.</center>";
							}
		 				 ?>
		 				
		 			</li>
		 		</ul>
		 	</div>
		 </div>
	</div>
</body>
</html>