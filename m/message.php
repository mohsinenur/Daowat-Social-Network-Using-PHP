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

include ( "./inc/headerfmnoti.inc.php");
//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");
?>

<?php 
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
				die();
			}
		}
	}

	$get_title_info = mysql_query("SELECT * FROM users WHERE username='$username'");
	$get_title_fname = mysql_fetch_assoc($get_title_info);
	$title_fname = $get_title_fname['first_name'];
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> - Daowat</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style>
	#online,#offline{
	float:left;
	width:12px;
	height:12px;
	}
	</style>
	
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>
			<div  style="margin: 10px; cursor: pointer;">
				<?php 
				if (isset($_GET['u'])) {
					$username = mysql_real_escape_string($_GET['u']);
					if (ctype_alnum($username)) {
						//check user exists
						$check = mysql_query("SELECT * FROM users WHERE username='$username'");
						if (mysql_num_rows($check)===1) {
							$get = mysql_fetch_assoc($check);
							$username = $get['username'];
							$fullname = $get['first_name'];
							$getid = $get['id'];
							$profile_pic_dbs= $get['profile_pic'];
							//check for propic delete
							$pro_changeds = mysql_query("SELECT * FROM posts WHERE added_by='$username' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 2");
							$get_pro_changeds = mysql_fetch_assoc($pro_changeds);
							$pro_nums = mysql_num_rows($pro_changeds);
							if ($pro_nums != 0) {
								
								$pro_changed_dbs = $get_pro_changeds['photos'];
								if ($pro_changed_dbs != $profile_pic_dbs) {
									$profile_pics = "http://www.daowat.com/img/default_propic.png";
								}else {
									$profile_pics = 'http://www.daowat.com/userdata/profile_pics/'.$profile_pic_dbs.'';
								}
							}else {
								$profile_pics = "http://www.daowat.com/img/default_propic.png";
							}
							//Checking if user is not sending himself a privet message
							if ($username != $user) {
								if (isset($_POST['submit'])) { 
									$msg_body = ($_POST['msg_body']);
									$msg_body = trim($msg_body);
									$msg_body = mysql_real_escape_string($msg_body);
									$date = date("Y-m-d");
									$opened = "no";

									if ($msg_body == "") {
										echo "<div class='frndPok_errorecho' style=' margin: 15px 0 0 0;'>Please write a message!</div></br>";
									}else {
									$messages = mysql_query("INSERT INTO pvt_messages VALUES ('','$user','$username','$msg_body','$date','NOW()','$opened','')");
									header("location: message.php?u=$username");
									}
								}
								
								echo "<div style=' margin-bottom: 10px;'><img src='$profile_pics' style= 'border-radius: 4px; float: left; border: 1px solid #ddd;' title=\"$fullname\" height='38' width='38'  /><div style='font-size: 20px;  margin-left: 48px;'><a href='profile.php?u=$username' style='text-decoration: none; color: #4B4B4B;'>$fullname</a>"; 
								$conn = new PDO("mysql:host=localhost;dbname=daowatco_td","daowatco_td","sinEmi4334222");
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sql="select DATE_FORMAT(chatOnlineTime,'%Y-%m-%e %H:%i:%s') as dd,DATE_FORMAT(now(),'%Y-%m-%e %H:%i:%s') as now from users where username='$username'";
								$result=$conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
								$getonlineuserTime = mysql_query("SELECT chatOnlineTime FROM users WHERE username='$username'") or die(mysql_error());
								$rowTime = mysql_fetch_assoc($getonlineuserTime);
								$activeTime = $rowTime['chatOnlineTime'];
								foreach($result as $v){
								$now=date_create($v["now"]);
								$date=date_create($v["dd"]);
								$nowT = $v["now"];
								$ddT = $v["dd"];
								$diff=date_diff($now,$date);
								
								
								$time[]=$diff->format("%ad %hh %im %ss")." ago";
								
								
								/*if($diff < '86400') {
								$time[]=$diff->format("%a days")." ago";
								}else if() {
								$time[]=$diff->format("%h hours")." ago";
								}else if() {
								$time[]=$diff->format("%i minutes")." ago";
								}else  {
								$time[]=$diff->format("%s seconds")." ago";
								}
								*/
								
								}
								
								$i=0;
								echo "<span style='padding: 8px; font-size: 12px; color: #818181;' >active ".$time[$i]."</span>";$i++;
								$conn=null;
								
								echo"</div>
								</div><br />
								<div>
									<ul>
										<li style='line-height: 18px;'>";
								//getting conversation
								$get_message = mysql_query("SELECT * FROM pvt_messages WHERE (user_from='$user' AND user_to='$username') OR (user_from='$username' AND user_to='$user') ORDER BY id ASC LIMIT 50");
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
										$photosTag = "<img style='width: 15px; height: 15px; margin: -3px 3px;' src='./img/emo/".$row['photos']."'/>";
										$msg_body = str_replace($chars, $photosTag, $msg_body);
									}
									
										echo "
										<div style='display: flex; padding: 8px 0; border-top: 1px solid #ddd;''>
											<div style='width: 100%;'>
												<b><a href='profile.php?u=$user_by' style='text-decoration: none; font-size: 14px; color: #0B810B;' title=\"Go to $posted_by's Profile\" class='posted_by'>$posted_by</a></b>
												<a href='deleteMsg.php?msgid=".$msg_id."' style='float: right; color: #848282; font-weight: bold; text-decoration:none;' title='Click to delete';>X</a>
												";
												echo "<div style='font-size: 14px;' >".nl2br($msg_body)."</div>
												<div style='font-size: 10px;'>".$date_added."</div>";
												
												echo "
											</div>
										</div>";
									
									
								}
								echo "</li>
								</ul>
								</div>";
								}else {
									echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'>No conversation!</center>";
								}

								echo "
								<div class='message_box'>
								<form action='message.php?u=$username' method='POST' >";
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
										echo "<div style='display: flex;'>
										<div style='margin: 5px 0px; width: 100%;'><textarea class='msgSendTxtarea' name='msg_body' autofocus ></textarea><p />
										</div><div  style='margin: 5px 0px 0px 8px;'><input type='submit' name='submit' class='msgSendButton' value='Send'/><br />
										</div></div>
										</form>";
									}else {
										if (($gender_value == 2) && ($user_gender_value == 2)) {
											echo "<div style='display: flex;'>
										<div style='margin: 5px 0px; width: 100%;'><textarea class='msgSendTxtarea' name='msg_body' autofocus></textarea><p />
											</div><div  style='margin: 5px 0px 0px 8px;'><input type='submit' name='submit' class='msgSendButton' value='Send' /><br />
											</div></div>
											</form>";
										}else {
										echo "<p style='text-align: center;padding: 30px 0; font-weight: bold; font-size: 15px;'>Sorry! You can not make conversation with $fullname .</p>";
										}
									}
								}else {
									echo "<div style='display: flex;'>
										<div style='margin: 5px 0px; width: 100%; float: left;'><textarea class='msgSendTxtarea' name='msg_body' autofocus ></textarea><p /><br />
									</div><div  style='margin: 5px 0px 0px 8px;'><input type='submit' name='submit' class='msgSendButton' value='Send'/><br />
									</div></div>
									</form>";
								}

								
								echo "</div>
								</div>
								";

							}else {
								header("location: login.php");
							}
						}else {
								header("location: login.php");
							}
					}else {
								header("location: login.php");
							}
				}else {
								header("location: login.php");
							}
				?>
			</div>
		</div>

</body>