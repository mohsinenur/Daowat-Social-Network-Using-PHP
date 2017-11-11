<?php include ( "./inc/connect.inc.php"); ?>
<?php

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}

if (isset($_REQUEST['plikep'])) {
	$getid = $_REQUEST['plikep'];
}else {
	header('location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Who like this</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<ul>
					<li><a href="index.php">Daowat</a></li>
					<li><a href="newsfeed.php">Newsfeed</a></li>
					<li><a href="profile.php?u=$user">Profile</a></li>
					<li><a href="messages.php">Message</a></li>
					<li> <a href="followRequest.php">Friends</a></li>
					<li> <a href="notification.php">Notification</a></li>
					<li> <a href="search.php">Search</a></li>	
					
				</ul>
			</nav>
		</div>

	<div style="width: 100%;">
		<div style="margin: 10px; line-height: 18px;">
		<h2 style='font-size: 20px';>Post Like</h2><br />
			<?php 
			$user_from = "";
			//grab the message for the logged in user
			$grab_post = mysql_query("SELECT * FROM post_likes WHERE post_id='$getid' ORDER BY id DESC");
			$numrows = mysql_num_rows($grab_post);
			if ($numrows != 0) {
			while ($get_like = mysql_fetch_assoc($grab_post)) {
				$user_from = $get_like['user_name'];

				//geting user from info
				$get_user_info = mysql_query("SELECT * FROM users WHERE username='$user_from'");
				$get_info = mysql_fetch_assoc($get_user_info);
				$profile_picuser_from_db= $get_info['profile_pic'];
				$msg_by_fname = $get_info['first_name'];
				
				//check for userfrom propic delete
					$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_from' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
					$get_pro_changed = mysql_fetch_assoc($pro_changed);
					$pro_num = mysql_num_rows($pro_changed);
					if ($pro_num == 0) {
						$profile_picuser_from = "http://www.daowat.com/img/default_propic.png";
					}else {
						$pro_changed_db = $get_pro_changed['photos'];
					if ($pro_changed_db != $profile_picuser_from_db) {
						$profile_picuser_from = "http://www.daowat.com/img/default_propic.png";
					}else {
						$profile_picuser_from = "http://www.daowat.com/userdata/profile_pics/".$profile_picuser_from_db;
					}
					}
								
				?>

				<?php
				
					echo "
					<a href='profile.php?u=$user_from' style='text-decoration: none;'>
					<form method='POST' action='messages.php'>
					<div style='display: flex; padding: 8px 0; border-top: 1px solid #ddd;'> ";
							echo "<div style='margin-right: 10px;'>
							<img src='$profile_picuser_from' style= 'border-radius: 4px'; title=\"$msg_by_fname\" height='42' width='42'  />
							</div>";
			
						echo "<div><b style='font-size: 14px; font-weight: bold; color: #0B810B;'>$msg_by_fname</b> <br>";
						
						if ($user == $user_from) {
								echo "<b style='font-size: 12px; font-weight: bold; color: #818181;'></b>";
							}else {
								$check_if_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user' AND user_to='$user_from') ORDER BY id DESC LIMIT 2");
								$num_follow_found = mysql_num_rows($check_if_follow);
								if ( $num_follow_found != "" ) {
									$check_if_friend = mysql_query("SELECT * FROM follow WHERE (user_from='$user_from' AND user_to='$user') ORDER BY id DESC LIMIT 2");
									$num_friend_found = mysql_num_rows($check_if_friend);
									if ( $num_friend_found != "" ) {
										echo "<b style='font-size: 12px; font-weight: bold; color: #818181;'>Friend</b>";
									}else {
										echo "<b style='font-size: 12px; font-weight: bold; color: #818181;'>Following</b>";
									}
									
								}else {
									$if_uname_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user_from' AND user_to='$user') ORDER BY id DESC LIMIT 2");
									$uname_follow_found = mysql_num_rows($if_uname_follow);
									if ( $uname_follow_found != "" ) {
										echo "<b style='font-size: 12px; font-weight: bold; color: #818181;'>Follows You</b>";
									}else {
										echo "<b style='font-size: 12px; font-weight: bold; color: #818181;'>Unknown</b>";
									}
									}
								
								}
						
						echo "</div>";
					echo "
					</div>
					</form>
					</a>
					";
			}
			}else {
				echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><p>No more people like this.</p></center>";
			}

			?>
		</div>

</div>
<div>
		<?php include("./inc/footer.inc.php") ?>
	</div>
</div>
</body>
</html>