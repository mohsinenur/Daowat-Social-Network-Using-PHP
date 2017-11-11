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

if (isset($_POST['newPost'])) {
	header("location: composepost.php");
}


$photoLocat = 'http://www.daowat.com';

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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Newsfeed - Daowat</title>
	<meta charset="utf-8" />
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
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
</head>
<body>
<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<ul>
					<li><a href="index.php">Daowat</a></li>
					<li ><a href="newsfeed.php" style="color: #2AED25; font-weight: bold;">Newsfeed</a></li>
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
					<li> <a href="search.php">Search</a></li>
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
					<input type="submit" name="newPost" value="New Post" class="postSubmit" >
				</div>
				
			</form>
		</div>
		<?php 

		//for getting post

		$getposts = mysql_query("SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND daowat_give ='0' AND note='0' ORDER BY id DESC") or die(mysql_error());
		
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
				
				if ($getpostsNum == 5){
					
					echo "
						<div style='margin: 10px 0px; background-color: white; padding: 10px;' >
							<p>People You May Know</p>
							<div>";
								include ( "./inc/pplumayknow.inc.php");
							echo "</div>
						</div>
			
					";
				}
				
				
				$newsfeedlastid = $row['id'];
				if ($getpostsNum == 10){
					break;
				}
			}
			echo '<div><li class="newsfeedmore" id="'.$newsfeedlastid.'" >Show More</li>';
			echo '</ul>';
			echo '
			</div>
		</br>
	</div>
</div>
</div>';
?>
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
<div>
		<?php include("./inc/footer.inc.php") ?>
		</div>
</body>
</html>