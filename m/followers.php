<?php include ( "./inc/connect.inc.php"); ?>
<?php  

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
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
				$firstname = $get['first_name'];
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
	<title><?php echo $title_fname; ?> • Daowat</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>

	<?php 

	$result = mysql_query("SELECT * FROM users WHERE username='$username'");
	$num = mysql_num_rows($result);
	if ($num == 1) {

	include ( "./inc/profile.inc.php");
	echo '
		<div class="pro_header"> 
			<nav class="pro_hdr_menu">
				<ul>
					<li ><a href="profile.php?u='.$username.'">Post</a></li>
					<li ><a href="about.php?u='.$username.'">About</a></li>
					<li style="border-bottom: 4px solid #2AED25; color: #2AED25;"><a href="friends.php?u='.$username.'">Friend</a></li>
					<li><a href="photo.php?u='.$username.'">Photo</a></li>
					<li><a href="note.php?u='.$username.'">Note</a></li>
				</ul>
			</nav>
	</div>';
	$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
	$msg_count = mysql_num_rows($get_msg_num);
	if (($msg_count >=1 ) || ($username == $user)) {
			$count = "";
			$friends_num = 1;
			$count_frnd_num = 0;
				//count followers
				$queryfollowers = "SELECT * FROM follow WHERE user_to='$username' ORDER BY id DESC";
				$queryfollowers = mysql_query($queryfollowers ) or die ("could not count");
				$countfollowers = mysql_num_rows($queryfollowers );
				//count following
				$queryfollowing = "SELECT * FROM follow WHERE user_from='$username' ORDER BY id DESC";
				$queryfollowing= mysql_query($queryfollowing) or die ("could not count");
				$countfollowing = mysql_num_rows($queryfollowing);
				
				//getting all friend
				$queryfollowing2 = "SELECT * FROM follow WHERE user_from='$username' ORDER BY id DESC";
				$queryfollowing2= mysql_query($queryfollowing2) or die ("could not count");
				while ($row=mysql_fetch_array($queryfollowing2)) {
						$user_from = $row['user_from'];
						$user_to = $row['user_to'];
						//user to if friend 
						
						$if_user_to_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user_to' AND user_to='$user_from')");
						$count_user_to_follow = mysql_num_rows($if_user_to_follow);
						if ($count_user_to_follow != 0) {
						  $count_frnd_num = $friends_num++;
						}
					     }
					    //buddy list menu
				echo '<div>
					     <table class="friendsmenu">
							<tr>
								<th><a href="friends.php?u='.$username.'"><h1>Friends </br>'.$count_frnd_num.'</h1></a></th>
								<th ><a href="following.php?u='.$username.'" ><h1>Following </br>'.$countfollowing.'</h1></a></th>
								<th style="background-color: #686B68;"><a href="followers.php?u='.$username.'"><h1>Followers </br>'.$countfollowers.'</h1></a></th>
							</tr>
						</table>
					</div>';
					if ($countfollowers == 0){
						echo '<div class="search_banner"><p style="font-size: 15px; margin: 10px; font-weight: bold; color: #7B7B7B;">No followers found!<br></p>
						</div>';
					}else {
					echo ' <div class="frndList_container">
						';
					while ($row=mysql_fetch_array($queryfollowers )) {
						$user_from = $row['user_from'];
						$user_to = $row['user_to'];
						//spacif user profile
						$family_query = mysql_query("SELECT id,username,first_name,profile_pic,cover_pic,city,hometown,company,school,gender,verify_id FROM users where username='$user_from' ORDER BY id DESC LIMIT 1");
						$family_row = mysql_fetch_assoc($family_query) or die ("could not count");
						$id = $family_row['id'];
						$username = $family_row['username'];
						$first_name = $family_row['first_name'];
						$city = $family_row['city'];
						$hometown = $family_row['hometown'];
						$company = $family_row['company'];
						$school = $family_row['school'];
						$profile_picuser_from_db = $family_row['profile_pic'];
						$cover_picuser_from_db = $family_row['cover_pic'];
						$verify_id_user = $family_row['verify_id'];
						
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
							echo '
								<div class="frndList">
									<div class="frndListS">
										<div class="frndListImg">
											<a href="profile.php?u='.$username.'"><img src='.$profile_picuser_from.' /></a>
										</div>
											<div class="frndListNm">';
											//if ($verify_id_user == 'yes') {
											//	echo '<div style="display: flex;"><div class="frndListNmTxt" style="margin-right: 3px; float: left;"><a href="profile.php?u='.$username.'">'.$first_name.'</a></div><div class="verifiedicon" style="background: url(http://www.daowat.com/img/verifiLogo.png) repeat; background-size: cover !important; margin-top: 1px; width: 17px; height: 17px;" title="Verified profile"></div></div>';
											//}else {
												echo '<span class="frndListNmTxt"><a href="profile.php?u='.$username.'">'.$first_name.'</a></span>';
											//}
										echo '</div>
										<div class="frndListBtn">
										</div>
									</div>

								</div>

							';
						}
						}
				}else {
				echo "<p style='text-align: center; color: #7B7B7B; margin: 30px; font-weight: bold; font-size: 20px;'>Sorry! Nothing to view. </p>";
		}
	}else {
	header("location: profile.php?u=$user");
} 

?>

</div>
<div>
		<?php include("./inc/footer.inc.php") ?>
	</div>
</body>
</html>