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
	<title><?php echo $title_fname; ?> • Daowat</title>
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
<div id="top"></div>
<?php 
$result = mysql_query("SELECT * FROM users WHERE username='$username'");
	$num = mysql_num_rows($result);
	if ($num == 1) {
			include ( "./inc/header.inc.php");
			include ( "./inc/profile.inc.php");
			echo '<li style="float: right;">
							
							
							<div >
								<nav>
								<ul>
								<li><a href="daowat.php?u='.$username.'">Daowat</a></li>
								<li><a href="note.php?u='.$username.'">Note</a></li>
								<li><a href="photo.php?u='.$username.'">Photo</a></li>
								<li><a href="friends.php?u='.$username.'">Friend</a></li>
								<li><a href="about.php?u='.$username.'">About</a></li>
								<li><a href="profile.php?u='.$username.'" style="background-color: #cdcdcd; color: #0b810b">Post</a></li>
								</ul>
								</nav>
							</div>
							
						</li>
					</ul>
					
					</div>
				</div>
			</div>';
		echo '	
		<div id="top">
			<div style="width: 560px; margin: 0 auto;">';
			$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
			$msg_count = mysql_num_rows($get_msg_num);
			if (($msg_count >=1 ) || ($username == $user)){
				echo '
					<div class="postForm">
					<form action="profile.php?u='.$username.'" method="POST" enctype="multipart/form-data">
						<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" rows="4" cols="58"  class="postForm_text" placeholder="What you are thinking..."></textarea>
						<input type="submit" name="send" value="Post" class="postSubmit" >
					</form>
					</div>
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
						$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to,newsfeedshow ) VALUES('$post', '$date_added','$added_by', '$user_posted_to', '$newsfeedshow')";
						$query = mysql_query($sqlCommand) or die (mysql_error());
					}

				//for getting post

				$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' AND daowat_give='0' AND note='0' AND report='0' ORDER BY id DESC LIMIT 9") or die(mysql_error());
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
						<a href="#top" class="backtotop">top</a>
						</br>
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
</body>
</html>