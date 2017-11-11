<?php include ( "./inc/connect.inc.php" ); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}


//Check whether the user has uploaded a cover pic or not
$check_pic = mysql_query("SELECT cover_pic FROM users WHERE username='$user'");
$get_pic_row = mysql_fetch_assoc($check_pic);
$cover_pic_db = $get_pic_row['cover_pic'];
//check for userfrom propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user' AND (discription='updated his cover photo.' OR discription='updated her cover photo.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$cover_pic= "img/default_covpic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $cover_pic_db ) {
			$cover_pic= "img/default_propic.png";
		}else {
			$cover_pic= "userdata/profile_pics/".$cover_pic_db ;
		}
		}


//name query
$about_query = mysql_query("SELECT first_name FROM users WHERE username='$user'");
$get_result = mysql_fetch_assoc($about_query);
$first_name_user = $get_result['first_name'];


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
	<?php include ( "./inc/header.inc.php" ); ?>
		<div style="width: 900px; margin: 52px auto;">
			<div style="float: left;">
				<div class="homeLeftSideContent" >
					<div class="home_cov" style= "background: url(<?php echo $cover_pic; ?>) repeat center center;">
						<div style="float: left;">
							<img src="<?php echo $profile_pic; ?>" height="70" width="70" style="border-radius: 40px; margin: 20px 0 0 10px;border: 2px solid #fff;" />
						</div>
						<div class="home_cov_data">
							<a href="profile_update.php" class="home_cov_nm" >Edit your profile</a><br>
						</div><br>
						<div class="homenavemanu">
							<div >
								<div ><a href="index.php">Daowat</a></div>
								<div ><a href="newsfeed.php">Newsfeed</a></div>
								<div ><a href="profile.php?u=<?php echo $user; ?>">Me</a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="settingsleftcontent" style="width: 301px;  margin-top: 15px;">
					<?php include './inc/profilefooter.inc.php'; ?>
				</div>
			</div>
			<div style="float: right;">
				 <div class="notificationbox">
				 	<h2  style="float: left; color: #0B810B;">Follow Request</h2>
				 	<div style='max-height: 500px; overflow: auto; width: 556px;'>
				 		<ul>
				 			<li style='line-height: 8px;'>
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
			$profile_pic = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db) {
			$profile_pic = "img/default_propic.png";
		}else {
			$profile_pic = "userdata/profile_pics/".$profile_pic_db;
		}
		}
											echo "<div style='margin: 10px; padding: 7px 0px;'>";
													echo "<div style=' float: left; margin: 0px 10px 0 0;'><img src='$profile_pic' style= 'border-radius: 22px';  height='38' width='38'  /></div>";
												echo "<div style='margin: 0 0 0 50px; line-height: 1.4;'>";
													echo "<b><a href='profile.php?u=$user_from' style='text-decoration: none; font-size: 15px; color: #0B810B;'  class='posted_by'>$user_from_fname</a></b><span style='font-size: 15px; margin-left: 10px;'>".$comment_disp."</span>
													"; 
														
													echo "<br>";
														echo "<span style='font-size: 10px; color: #7C7979;'>".$time."</span>
														";
												echo "</div>";
											echo "</div>";
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
		</div>
	</div>
</body>
</html>