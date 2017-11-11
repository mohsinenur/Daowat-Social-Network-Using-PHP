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
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<ul>
					<li><a href="index.php">Daowat</a></li>
					<li><a href="newsfeed.php">Newsfeed</a></li>
					<?php 
						if ($username == $user) {
						    echo "<li ><a style='color: #2AED25; font-weight: bold;' href='profile.php?u=".$user."'>Profile</a></li>";
						}else  {
						    echo "<li><a style='font-weight: bold;' href='profile.php?u=".$user."'>Profile</a></li>";
						}
					?>
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

		<div id="top"></div>
		<?php 
		$result = mysql_query("SELECT * FROM users WHERE username='$username'");
			$num = mysql_num_rows($result);
			if ($num == 1) {
				include ( "./inc/profile.inc.php");
				echo '
				<div class="pro_header"> 
					<nav class="pro_hdr_menu">
						<ul>
							<li style="border-bottom: 4px solid #2AED25; color: #2AED25; font-weight: bold;"><a href="profile.php?u='.$username.'">Post</a></li>
							<li ><a href="about.php?u='.$username.'">About</a></li>
							<li><a href="friends.php?u='.$username.'">Friend</a></li>
							<li><a href="photo.php?u='.$username.'">Photo</a></li>
							<li><a href="note.php?u='.$username.'">Note</a></li>
						</ul>
					</nav>
				</div>';
				echo '	
				<div id="top">
					<div style="margin: 0 auto;">';
					$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
					$msg_count = mysql_num_rows($get_msg_num);
					if (($msg_count >=1 ) || ($username == $user)){
						echo '
						';
					}else {
						//nothing
					}
						echo '<div class="profilePosts">';

						//post update
							$profilehmlastid = "";
							$post = htmlspecialchars(@$_POST['post'], ENT_QUOTES);
							$post = trim($post);
							$post = mysql_real_escape_string($post);

							if ($post != "") {
								$date_added = date("Y-m-d");
								$added_by = $user;
								$user_posted_to = $username;
								if ($username == $user) {
									$newsfeedshow = '1';
								}else {
									$newsfeedshow = '0';
								}
								$sqlCommand = "INSERT INTO posts VALUES('', '$post', '$date_added', '$added_by', '$user_posted_to', '', '', '$newsfeedshow','','','')";
								$query = mysql_query($sqlCommand) or die (mysql_error());
							}

						//for getting post

						$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' AND note='0' AND daowat_give='0' AND report='0' ORDER BY id DESC LIMIT 10") or die(mysql_error());
						$count_post = mysql_num_rows($getposts);
						echo '<ul id="profilehmpost">';
						while ($row = mysql_fetch_assoc($getposts)) {
								include ( "./inc/newsfeed.inc.php");
								$profilehmlastid = $row['id'];
								$profilehm_uname = $row['user_posted_to'];
							}
							if ($count_post >= 9) {
								echo '<li class="profilehmmore" id="'.$profilehmlastid.'" >Show More</li>';
								echo '</ul>';
								echo '
								</div>
							</div>
						</div>
					</div>
					</div>';
							}else {
							echo '</ul>';
							echo '
							</div>
						</br>
					</div>
				</div>
			</div>
			</div>';
							}

			}
			else {
				header("location: profile.php?u=$user");
			}
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('.profilehmmore').live('click',function() {
					var profilehmlastid = $(this).attr('id');
					$.ajax({
						type: 'GET',
						url: 'profilehmmore.php',
						data: 'profilehmlastid='+profilehmlastid,
						beforeSend: function() {
							$('.profilehmmore').html('Loading ...');
						},
						success: function(data) {
							$('.profilehmmore').remove();
							$('#profilehmpost').append(data);
						}
					});
				});
			});
		</script>
	</div></br>
	<div>
		<?php include("./inc/footer.inc.php") ?>
	</div>
</body>
</html>