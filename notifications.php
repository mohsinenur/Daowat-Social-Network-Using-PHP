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
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('body').on('keydown', '#search', function(e) {
		    console.log(this.value);
		    if (e.which === 32 &&  e.target.selectionStart === 0) {
		      return false;
		    }  
		  });
		});
	</script>
	<script type="text/javascript">
	function clean (e) {
		var textfield = document.getElementById(e);
		var regex = /fuck/gi;
		textfield.value = textfield.value.replace(regex, "");
	}
	</script>
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
</head>
<body>
	<?php include ( "./inc/header.inc.php" ); ?>
		<div style="width: 900px; margin: 52px auto;">
			<div style="float: left;">
				<div class="homeLeftSideContent">
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
				<div class="settingsleftcontent" style="width: 301px; margin-top: 15px;">
					<?php include './inc/profilefooter.inc.php'; ?>
				</div>
			</div>
			<div style="float: right;">
				 <div class="notificationbox">
				 	<a href="notifications.php"><h2 style="float: left; color: #0B810B;">Daowat Notification&nbsp;|</h2></a><a href="notification.php"><h2 style="float: left;color: #848684;">&nbsp;Post Notification</h2></a>
				 	<div style='max-height: 500px; overflow: auto; width: 556px;'>
				 		<ul>
				 			<li style='line-height: 8px;'>
				 				<?php 
				 					//getting post comment
									$get_notipost = mysql_query("SELECT * FROM post_comments WHERE posted_to='$user' AND posted_by != '$user' ORDER BY id DESC LIMIT 50");
									$count_notipost = mysql_num_rows($get_notipost);
				 					//getting daowat comment
									$get_notidwt = mysql_query("SELECT * FROM daowat_comments WHERE daowat_to='$user' AND daowat_by != '$user' ORDER BY id DESC LIMIT 50");
									$count_notidwt = mysql_num_rows($get_notidwt);
									$count = $count_notidwt + $get_notidwt;
									if ($count != 0) {
										//getting daowat noti
										while ($noti_dwt = mysql_fetch_assoc($get_notidwt)) {
											$daowat_id = $noti_dwt['daowat_id'];
											$daowat_body = $noti_dwt['daowat_body'];
											$time = $noti_dwt['time'];
											$user_by = $noti_dwt['daowat_by'];
											$user_to = $noti_dwt['daowat_to'];
											$get_user_info = mysql_query("SELECT * FROM users WHERE username='$user_by'");
											$get_info = mysql_fetch_assoc($get_user_info);
											$profile_pic_db= $get_info['profile_pic'];
											$posted_by = $get_info['first_name'];
											
											//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
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
											
											$get_descrip = mysql_query("SELECT * FROM daowat WHERE id='$daowat_id'");
											$get_description = mysql_fetch_assoc($get_descrip);
											$discription = $get_description['photos'];
											if ($discription == "") {
												$comment_disp = "commented on your daowat";
												$posted_pic = "";
											}else {
												$comment_disp = "commented on your daowat photo";
												$posted_pic = "userdata/daowat_pics/".$discription;
											}
											if ($discription == "") {
												$comment_disp = "commented on your daowat";
											}else {
												$comment_disp = "commented on your daowat photo";
											}

											echo "<div style='margin: 10px; padding: 7px 0px;'>";
													echo "<div style=' float: left; margin: 0px 10px 0 0;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$posted_by\" height='38' width='38'  /></div>";
												
												echo "<div style='margin: 0 0 0 50px; line-height: 1.4;'>";
													echo "<b><a href='profile.php?u=$user_by' style='text-decoration: none; font-size: 14px; color: #0B810B;' title=\"Go to $posted_by's Profile\" class='posted_by'>$posted_by</a></b><span style='font-size: 15px; margin-left: 10px;'>".$comment_disp."</span>
													"; 
														 if ($discription == "") {
														 	//
														 }else {
														 	echo "<a href='viewDaowat.php?did=".$daowat_id."'><img src='$posted_pic' style= 'float: right;' height='55' width='65' /></a>";
														 }
													echo "<br>";
														echo "<span style='line-height: 1.5; color: #444;' >".nl2br($daowat_body)."</span><br>
														<span style='font-size: 10px; color: #7C7979;'>".$time."</span>
														";
												echo "</div>";
											echo "</div>";
										}
									}else {
										echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br><br>You have no notification!.</center>";
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