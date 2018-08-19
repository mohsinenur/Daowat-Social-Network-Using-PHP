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

	$get_title_info = mysql_query("SELECT * FROM users WHERE username='$username'");
	$get_title_fname = mysql_fetch_assoc($get_title_info);
	$title_fname = $get_title_fname['first_name'];

	
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

//Check whether the user has uploaded a profile pic or not
$check_pic = mysql_query("SELECT profile_pic FROM users WHERE username='$user'");
$get_pic_row = mysql_fetch_assoc($check_pic);
$profile_pic_db = $get_pic_row['profile_pic'];
//check for userfrom propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user' AND (discription='changed his profile picture.' OR discription='changed her profile picture.' ORDER BY id DESC LIMIT 1");
						//$get_pro_changed = mysql_fetch_assoc($pro_changed);
						if ($pro_changed){
						   $get_pro_changed = mysql_fetch_assoc($pro_changed);
						}else $get_pro_changed ='';
		//$pro_num = mysql_num_rows($pro_changed);
		if($pro_changed == true){
			$pro_num = mysql_num_rows($pro_changed);
		}else $pro_num = 0;
		if ($pro_num == 0) {
			$profile_pic= "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db ) {
			$profile_pic= "img/default_propic.png";
		}else {
			$profile_pic= "userdata/profile_pics/".$profile_pic_db ;
		}
		}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Newsfeed • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script>
		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/IEMobile/i.test(navigator.userAgent)){
		if(document.URL !="http://m.daowat.com")
		{
		window.location ="http://m.daowat.com";
		}
		}
	</script>
	<style type="text/css">
		table {
		  table-layout: fixed;
		}
	</style>
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
<?php include ( "./inc/header.inc.php");?> 
<div style="width: 1220px; margin: 52px auto;">

<table>
	<tbody>
			<tr>
				<td style="vertical-align: top; padding: 0px 10px;" >
					<div style="">
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
								<div ><a href="newsfeed.php" style="color: #0B810B">Newsfeed</a></div>
								<div ><a href="profile.php?u=<?php echo $user; ?>">Me</a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="settingsleftcontent" style="width: 301px; margin-top: 15px;">
					<?php include './inc/profilefooter.inc.php'; ?>
				</div>
			</div>
    		<div>
				</td>
				<td style="vertical-align: top; padding: 0px 10px;" >
					<div style="width: 560px; margin: 0px auto;">
		<div class="postForm">
			<form action="newsfeed.php" method="POST">
				<textarea name="post" rows="4" cols="58"  class="postForm_text" placeholder="What you are thinking..."></textarea>
				<?php
					echo'<input type="submit" name="send" value="Post" class="postSubmit" >'; 
				?>
			</form>
		</div>
		<div class="profilePosts">
		<?php 
		//post update
		$profilehmlastid = "";
		//$post = ($_POST['post']);
		$post = isset($_POST['post']) ? $_POST['post'] : '';
		//$post = htmlspecialchars(@$_POST['post'], ENT_QUOTES);
		$post = trim($post);
		$post = mysql_real_escape_string($post);

		if ($post != "") {
			$date_added = date("Y-m-d");
			$added_by = $user;
			$user_posted_to = $user;
			$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to) VALUES('$post', '$date_added','$added_by', '$user_posted_to')";
			$query = mysql_query($sqlCommand) or die (mysql_error());
		}

		//for getting post

		$getposts = mysql_query("SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND note='0' AND daowat_give='0' ORDER BY id DESC") or die(mysql_error());
		
		echo '<ul id="frndpost">';
		//declear variable
		$getpostsNum= 0;
		while ($row = mysql_fetch_assoc($getposts)) {
				$added_by = $row['added_by'];
				if ($added_by == $user) {
					include ( "./inc/newsfeed.inc.php");
					$getpostsNum++;
					
				}else {
					$checkDeactiveUser= mysql_query("SELECT * FROM users WHERE username = '$added_by'") or die(mysql_error());
					$checkDeactiveUser_row = mysql_fetch_assoc($checkDeactiveUser);
					$activeOrNot = $checkDeactiveUser_row ['activated'];
					if ($activeOrNot != '0') {					
						$check_if_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user' AND user_to='$added_by ') ORDER BY id DESC LIMIT 2");
						$num_follow_found = mysql_num_rows($check_if_follow);
						if ($num_follow_found != "") {
						include ( "./inc/newsfeed.inc.php");
						$getpostsNum++;
					}
				     }
				}
				
				$newsfeedlastid = $row['id'];
				if ($getpostsNum == 10){
					break;
				}
			}
			echo '<li class="newsfeedmore" id="'.$newsfeedlastid.'" >Show More</li>';
			echo '</ul>
			</div>';
			
		echo'</br>
	</div>
</div>
				</td>
				<td style="vertical-align: top; padding: 0px 10px;" >
				<div style="">
				<div  style="padding: 10px; height: 290px;" class="homeLeftSideContent" >
				<p style="padding: 4px 0; font-weight: bold; font-size: 16px;" >People You May Know</p>';
					include ( "pplumayknow.inc.php");
			echo'</div>
			</div>
			</div>
		</div>
	</div>';
?>
				</td>
			</tr>
	</tbody>
</table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.newsfeedmore').live('click',function() {
			var newsfeedlastid = $(this).attr('id');
			$.ajax({
				type: 'GET',
				url: 'newsfeedmore.php',
				data: 'newsfeedlastid='+newsfeedlastid,
				beforeSend: function() {
					$('.newsfeedmore').html('Loading ...');
				},
				success: function(data) {
					$('.newsfeedmore').remove();
					$('#frndpost').append(data);
				}
			});
		});
	});
</script>
</body>
</html>
