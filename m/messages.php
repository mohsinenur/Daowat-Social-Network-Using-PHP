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

//read all message
$setopened_query = mysql_query("UPDATE pvt_messages SET opened='yes' WHERE user_to='$user'");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

//chat count 
$buddyList = '0';
$getonlineuser = mysql_query("SELECT first_name,username FROM users WHERE (chatOnlineTime>=now()-300)") or die(mysql_error());

if (mysql_num_rows($getonlineuser) >= '2' ) {
	while ($row = mysql_fetch_assoc($getonlineuser)) {
		$usrnm= $row['username'];
		$check_if_friend = mysql_query("SELECT * FROM follow WHERE (user_from='$usrnm' AND user_to='$user') || (user_from='$user' AND user_to='$usrnm') ORDER BY id DESC LIMIT 2");
		if(mysql_num_rows($check_if_friend) >= '2') {
		$buddyList++;
		}
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="pro_body">
	<div class="pro_header">
		<nav class="pro_hdr_menu">
			<ul>
				<li><a href="index.php">Daowat</a></li>
				<li><a href="newsfeed.php">Newsfeed</a></li>
				<li><a href="profile.php?u=<?php echo "$user"; ?>">Profile</a></li>
				<?php 
					if($unread_numrows >= 1) 
					{
						echo "<li><a href='messages.php' style='color: yellow;'>Message(".$unread_msg_numrows.")</a></li>";
					}else {
						echo "<li><a href='messages.php' style='color: #2AED25; font-weight: bold;' >Message</a></li>";
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
			</ul>
		</nav>
	</div>
	<div style="width: 100%;">
		<div style="margin: 10px; line-height: 18px;">
		<h2 style='font-size: 20px';><a href="messages.php" style="text-decoration: none; color: #0b820b;">Message</a>  |  <a href="buddylist.php" style="text-decoration: none; color: #828182;" >Chat(<?php echo $buddyList; ?>)</a></h2><br />
			<?php 
			//grab the message for the logged in user
			$grab_messages = mysql_query("SELECT * FROM pvt_messages WHERE (user_to='$user' OR user_from='$user') ORDER BY id DESC");
			$numrows = mysql_num_rows($grab_messages);
			if ($numrows != 0) {
				$msg_arry = array($user);
			while ($get_msg = mysql_fetch_assoc($grab_messages)) {
				$users_id ='';
				$id = $get_msg['id'];
				$user_from = $get_msg['user_from'];
				$user_to = $get_msg['user_to'];
				$msg_body = $get_msg['msg_body'];
				$date = $get_msg['date'];
				$opened = $get_msg['opened'];
				if($user_from == $user) {
					$users_id = $user_to;
				}else if($user_to == $user) {
					$users_id = $user_from;
				}

				if(in_array($users_id, $msg_arry)) {
					//nothing
				}else {
				$msg_arry[] = $users_id;
				/*
				//making emo
				$emoticonQuery = mysql_query("SELECT * FROM emoticons");
				while ($row = mysql_fetch_assoc($emoticonQuery)) {
					$chars = $row['chars'];
					$photosTag = "<img style='width: 15px; height: 15px; margin: -3px 3px;' src='./img/emo/".$row['photos']."'/>";
					$msg_body = str_replace($chars, $photosTag, $msg_body);
				}
				*/
				//geting user from info
				$get_user_info = mysql_query("SELECT * FROM users WHERE username='$user_from'");
				$get_info = mysql_fetch_assoc($get_user_info);
				$profile_picuser_from_db= $get_info['profile_pic'];
				$msg_by_fname = $get_info['first_name'];
				//getting user to info
				$get_userto_info = mysql_query("SELECT * FROM users WHERE username='$user_to'");
				$userto_info = mysql_fetch_assoc($get_userto_info);
				$profile_picuser_to_db= $userto_info['profile_pic'];
				$msg_to_fname = $userto_info['first_name'];
				
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
				//check for userto propic delete
					$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_to' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
					$get_pro_changed = mysql_fetch_assoc($pro_changed);
					$pro_num = mysql_num_rows($pro_changed);
					if ($pro_num == 0) {
						$profile_picuser_to = "http://www.daowat.com/img/default_propic.png";
					}else {
						$pro_changed_db = $get_pro_changed['photos'];
					if ($pro_changed_db != $profile_picuser_to_db) {
						$profile_picuser_to = "http://www.daowat.com/img/default_propic.png";
					}else {
						$profile_picuser_to = "http://www.daowat.com/userdata/profile_pics/".$profile_picuser_to_db;
					}
					}
				
				if (strlen($msg_body) > 300) {
					$msg_body = substr($msg_body, 0, 300)." ...";
				}else{
					$msg_body = $msg_body;
					}
					if (@$_POST['setopened_' . $id . '']) {
						//update the message of private table
						$setopened_query = mysql_query("UPDATE pvt_messages SET opened='yes' WHERE id='$id'");
						if ($user_from == $user) {
							header("location: messages.php?u=$user_to");
						}else {
							header("location: messages.php?u=$user_from");
						}
						
					}
					//changing message to and from username
					$msgto = '';

					if ($user_from == $user) {
						$msgto = $user_to;
					}
					if ($user_to == $user) {
						$msgto = $user_from;
					}
					echo "
					<a href='message.php?u=$msgto' style='text-decoration: none;'>
					<form method='POST' action='messages.php'>
					<div style='display: flex; padding: 8px 0; border-top: 1px solid #ddd;'> ";
					if ($user_from == $user) {
							echo "<div  style='margin-right: 10px;'>
							<img src='$profile_picuser_to ' style= 'border-radius: 4px'; title=\"$msg_to_fname\" height='42' width='42'  />
							</div>";
						
						echo "<div><b style='font-size: 14px; font-weight: bold; color: #0B810B;'>$msg_to_fname</b> <br>
						
						<div style='font-size: 14px; color: #828282;' >".nl2br($msg_body)."</div>
						</div>";
					}else {
							echo "<div style='margin-right: 10px;'>
							<img src='$profile_picuser_from' style= 'border-radius: 4px'; title=\"$msg_by_fname\" height='42' width='42'  />
							</div>";
			
						echo "<div><b style='font-size: 14px; font-weight: bold; color: #0B810B;'>$msg_by_fname</b> <br>
						
						<div style='font-size: 14px; color: #EBBA7F;' >".nl2br($msg_body)."</div>
						</div>";
					}
					echo "
					</div>
					</form>
					</a>
					";
			}
			}
			}else {
				echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br>You have no message!</center>";
			}

			?>
		</div>
	</div>

</div>

</body>
</html>