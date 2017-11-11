<?php 

include ( "./inc/connect.inc.php");

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}

$username ="";
if (isset($_GET['u'])) {
	$username = mysql_real_escape_string($_GET['u']);
	if (ctype_alnum($username)) {
		//check user exists
		$check = mysql_query("SELECT username FROM users WHERE username='$username'");
		if (mysql_num_rows($check)===1) {
			$get = mysql_fetch_assoc($check);
			$username = $get['username'];
		}
		else {
			die();
		}
	}
}


//update online tine
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
//getting user first message
$get_first_message = mysql_query("SELECT * FROM pvt_messages WHERE user_to='$user' ORDER BY id DESC LIMIT 1");
$first_message_row = mysql_fetch_assoc($get_first_message);
$first_message_id = $first_message_row['id'];
$first_message_uname = $first_message_row['user_from'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body style="height: 90%;">
<?php include ( "./inc/header.inc.php"); ?>

<div style="width: 900px; margin: 52px auto;">
	<ul>
		<li style="float: left;">
			<div class="msgLeftside">
			<div>
				<h2 style='font-size: 20px';><a href="messages.php" style="text-decoration: none; color: #0b820b;">Message</a>  |  <a href="buddylist.php?u=<?php echo $first_message_uname; ?>" style="text-decoration: none; color: #828182;" >Chat(<?php echo $buddyList; ?>)</a></h2><br />
			</div>
				<?php 
				//grab the message for the logged in user
				$grab_messages = mysql_query("SELECT * FROM pvt_messages WHERE user_to='$user' OR user_from='$user' ORDER BY id DESC");
				$numrows = mysql_num_rows($grab_messages);
				if ($numrows != 0) {
					$msg_arry = array($user);
				while ($get_msg = mysql_fetch_assoc($grab_messages)) {
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
			$profile_picuser_from = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_picuser_from_db) {
			$profile_picuser_from = "img/default_propic.png";
		}else {
			$profile_picuser_from = "userdata/profile_pics/".$profile_picuser_from_db;
		}
		}
					//check for userto propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_to' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_picuser_to = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_picuser_to_db) {
			$profile_picuser_to = "img/default_propic.png";
		}else {
			$profile_picuser_to = "userdata/profile_pics/".$profile_picuser_to_db;
		}
		}
					
					?>

					<?php
					
					if (strlen($msg_body) > 50) {
						$msg_body = substr($msg_body, 0, 50)." ...";
					}else
						$msg_body = $msg_body;

						if (@$_POST['setopened_' . $id . '']) {
							//update the message of private table
							$setopened_query = mysql_query("UPDATE pvt_messages SET opened='yes' WHERE id='$id'");
							if ($user_from == $user) {
								header("location: messages.php?u=$user_to");
							}else {
								header("location: messages.php?u=$user_from");
							}
							
						}
						echo "
						<form method='POST' action='messages.php'> ";
						if ($user_from == $user) {
								echo "<div style='float: left; margin-left: 10px;'><img src='$profile_picuser_to ' style= 'border-radius: 22px'; title=\"$msg_to_fname\" height='45' width='45'  /></div>";
							
							echo "<div style='margin-left: 65px;'><b><a href='profile.php?u=$user_to' style='text-decoration: none; font-size: 14px; font-weight: bold; color: #0B810B;'>$msg_to_fname</a></b> <br>
							<input type='submit' name='setopened_$id' style='background: none; cursor: pointer; color: #828282; width: 270px; padding: 10px 5px 5px 0; text-align: left;border: none;font-size: 14px;' value='$msg_body' /><a href='messages.php?u=".$user_to."'><img src='img/msgout.png' style=' margin-left: -2px; height: 21px; width: 21px;'></a>
							</div>";
						}else {
								echo "<div style='float: left; margin-left: 10px;'><img src='$profile_picuser_from' style= 'border-radius: 22px'; title=\"$msg_by_fname\" height='45' width='45'  /></div>";
				
							echo "<div style='margin-left: 65px;'><b><a href='profile.php?u=$user_from' style='text-decoration: none; font-size: 14px; font-weight: bold; color: #0B810B;'>$msg_by_fname</a></b> <br>
							<input type='submit' name='setopened_$id' style='background: none; cursor: pointer; color: #EBBA7F; width: 270px; padding: 10px 5px 5px 0; text-align: left;border: none;font-size: 14px;' value='$msg_body' /><a href='messages.php?u=".$user_from."'><img src='img/msgin.png' style=' margin-left: -2px; height: 21px; width: 21px;'><a/>
							</div>";
						}
						echo "
						</form>
						";
					}
				}
				}else {
					echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br>You have no message!</center>";
				}

				?>
			</div>
		</li>
		<li style="float: right;">
			<div>
				<?php 
				if (isset($_GET['u'])) {
					$username = mysql_real_escape_string($_GET['u']);
					if (ctype_alnum($username)) {
						//check user exists
						$check = mysql_query("SELECT username, id,first_name FROM users WHERE username='$username'");
						if (mysql_num_rows($check)===1) {
							$get = mysql_fetch_assoc($check);
							$username = $get['username'];
							$fullname = $get['first_name'];
							$getid = $get['id'];
							//Checking if user is not sending himself a privet message
							if ($username != $user) {
								if (isset($_POST['submit'])) { 
									$msg_body = ($_POST['msg_body']);
									$msg_body = trim($msg_body);
									$msg_body = mysql_real_escape_string($msg_body);
									$date = date("Y-m-d");
									$opened = "no";

									if ($msg_body == "") {
										echo "<div class='frndPok_errorecho' style='width: 504px; margin: 15px 0 0 0;'>Please write a message!</div></br>";
									}else if (strlen($msg_body) < 2) {
										echo "<div class='frndPok_errorecho' style='width: 504px; margin: 15px 0 0 0;'>Your message can not be less than 2 characters in length!</div></br>";
									}else {
									$messages = mysql_query("INSERT INTO pvt_messages VALUES ('','$user','$username','$msg_body','$date','','$opened','')");
									echo "<div class='frndPok_succesecho' style='width: 416px; margin: 15px 0 0 0;'>Your message has been sent!</div></br>";
									header("location: messages.php?u=$username");
									}
								}
								echo "
								<div class='message_box'>
								<form action='messages.php?u=$username' method='POST' >
								<h2 style='font-size: 20px';>$fullname</h2><br />";
								//gettting user gender
								$get_user_gender = mysql_query("SELECT * FROM users WHERE username='$user'");
								$gender_user_row = mysql_fetch_assoc($get_user_gender);
								$user_gender_value = $gender_user_row['gender'];
								//gettting username gender
								$get_gender = mysql_query("SELECT * FROM users WHERE username='$username'");
								$gender_row = mysql_fetch_assoc($get_gender);
								$gender_value = $gender_row['gender'];
								if ($gender_value == 2) {
									$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
									$female_msg = mysql_num_rows($get_msg_num);
									if ($female_msg >=1 ) {
										echo "<textarea style='width: 467px; height: 60px; padding: 2px 10px; background-color: #EAEAEA; resize: none;font-weight: bold;font-size: 13px;color: #484848;' name='msg_body' placeholder='Enter the message you wish to send...'></textarea><p /><br />
										<input type='submit' name='submit' class='placeholder' value='Send Messege' style='float: right; margin-bottom: 10px;  cursor: pointer;'  /><br />
										<br />
										</form>";
									}else {
										if (($gender_value == 2) && ($user_gender_value == 2)) {
											echo "<textarea style='width: 467px; height: 60px; padding: 4px 4px; border: none; background-color: #EAEAEA; resize: none;font-weight: bold;font-size: 13px;color: #484848;' name='msg_body' placeholder='Enter the message you wish to send...'></textarea><p /><br />
											<input type='submit' name='submit' class='placeholder' value='Send Messege' style='float: right; margin-bottom: 10px;  margin-right: 13px;  cursor: pointer;'  /><br />
											<br />
											</form>";
										}else {
										echo "<p style='text-align: center;padding: 30px 0; font-weight: bold; font-size: 15px;'>Sorry! You can not make conversation with $fullname .</p>";
										}
									}
								}else {
									echo "<textarea style='width: 467px; height: 60px; padding: 4px 4px; border: none; background-color: #EAEAEA; resize: none;font-weight: bold;font-size: 13px;color: #484848;' name='msg_body' placeholder='Enter the message you wish to send...'></textarea><p /><br />
									<input type='submit' name='submit' class='placeholder' value='Send Messege' style='float: right; margin-right: 13px; margin-bottom: 10px;  cursor: pointer;'  /><br />
									<br />
									</form>";
								}
								
								echo "<div style='max-height: 356px; overflow: auto; width: 488px;'>
								<div>
									<ul>
										<li style='line-height: 8px;'>";
								//getting conversation
								$get_message = mysql_query("SELECT * FROM pvt_messages WHERE (user_from='$user' AND user_to='$username') OR (user_from='$username' AND user_to='$user') ORDER BY id DESC LIMIT 50");
								$count = mysql_num_rows($get_message);
								//deleting msg
								if ($count >= 51) {
									$result = mysql_query("DELETE FROM pvt_messages WHERE (user_from='$user' AND user_to='$username') OR (user_from='$username' AND user_to='$user') ORDER BY id ASC LIMIT 1");
								}
								if ($count != 0) {
								while ($msg = mysql_fetch_assoc($get_message)) {
									$msg_id = $msg['id'];
									$msg_body = $msg['msg_body'];
									$date_added = $msg['date'];
									$user_by = $msg['user_from'];
									$get_user_info = mysql_query("SELECT * FROM users WHERE username='$user_by'");
									$get_info = mysql_fetch_assoc($get_user_info);
									$profile_pic_db= $get_info['profile_pic'];
									$posted_by = $get_info['first_name'];
									//making emo
									$emoticonQuery = mysql_query("SELECT * FROM emoticons");
									while ($row = mysql_fetch_assoc($emoticonQuery)) {
										$chars = $row['chars'];
										$photosTag = "<img style='width: 15px; height: 15px; margin: -3px 3px;' src='img/emo/".$row['photos']."'/>";
										$msg_body = str_replace($chars, $photosTag, $msg_body);
									}
									//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_by ' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_pic = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db) {
			$profile_pic = "img/default_propic.png";
		}else {
			$profile_pic = "userdata/profile_pics/".$profile_pic_db;
		}
		}
									
									
										echo "
										<div style='margin: 10px;'>
											<div style=' float: left; margin: -7px 10px 0 0;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$posted_by\" height='38' width='38'  /></div>
											<div style='margin: 0 0 0 50px;'>
												<b><a href='profile.php?u=$user_by' style='text-decoration: none;  padding-left: 0px; font-size: 14px; color: #0B810B; font-weight: 700;' title=\"Go to $posted_by's Profile\" >$posted_by</a></b>
												<span style='font-size: 10px; margin-left: 10px;'>".$date_added."</span>
												<a href='deleteMsg.php?msgid=".$msg_id."' style='float: right; color: #848282; font-weight: bold; text-decoration:none; ' title='Click to delete';>X</a>
												
												<br><br>";
												if ($user_by == $user) {
													echo "<span style='line-height: 1.5; font-size: 13px; color: #373e4d;' >".nl2br($msg_body)."</span>";
												}else {
													echo "<span style='line-height: 1.5; background-color: #BABBBB; padding: 4px; border-radius: 3px; color: #373e4d; font-size: 13px;' >".nl2br($msg_body)."</span>";
												}
												echo "
											</div>
										</div><br>";
									
									
								}
								echo "</li>
								</ul>
								</div>
								</div>";
								}else {
									echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br><br>No conversation!</center>";
								}
								echo "</div>
								</div>
								";

							}else {
								header("location: messages.php");
							}
						}else {
							header('Location: messages.php');
						}
					}
				}
				?>
			</div>
		</li>
	</ul>
</div>
</body>
</html>