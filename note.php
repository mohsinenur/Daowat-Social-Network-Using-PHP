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
<?php

//note update
$error = "";
$post = htmlspecialchars(@$_POST['post'], ENT_QUOTES);
$post =  trim($post);
$post = mysql_real_escape_string($post);

if ($post != "") {
	$date_added = date("Y-m-d");
	$added_by = $user;
	$user_posted_to = $username;
	$discription = $_POST['privacy'];
	$sqlCommand = "INSERT INTO posts VALUES('', '$post','', '$date_added','', '$added_by', '$user_posted_to', '','','', '$discription', '','','','1','$_POST[privacy]')";
	$query = mysql_query($sqlCommand) or die (mysql_error());
	header("Location: note.php?u=$user");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
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
						<li><a href="note.php?u='.$username.'" style="background-color: #cdcdcd; color: #0b810b">Note</a></li>
						<li><a href="photo.php?u='.$username.'" >Photo</a></li>
						<li><a href="friends.php?u='.$username.'" >Friend</a></li>
						<li><a href="about.php?u='.$username.'" >About</a></li>
						<li><a href="profile.php?u='.$username.'" >Post</a></li>
						</ul>
						</nav>
					</div>
					
				</li>
			</ul>
			
			</div>
		</div>
	</div>';
		echo ''.$error.'
			<div style="width: 560px; margin: 0 auto;">';
				if ($user == $username) {
				echo '
				<div class="p_postForm ">
					<form action="" method="POST">
						<div>
						<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" rows="4" cols="58"  class="postForm_text" placeholder="Write your note here..."></textarea>
						<input type="submit" name="send" value="Note" class="postSubmit" >
						<select name="privacy" class="note_privacy">
							<option value="onlyme">Only Me</option>
							<option value="public">Public</option>
						</select>
						</div>
					</form>
				</div>';
			}else {
				//nothing
			}
				echo '<div class="profilePosts">';

				//for getting note
					$noteshowmorelastid = "";
					if ($user == $username) {
						$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' && note='1' ORDER BY id DESC LIMIT 5") or die(mysql_error());
					}else {
						$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' && note='1' && note_privacy='public' ORDER BY id DESC LIMIT 5") or die(mysql_error());
					}
				$count_note = mysql_num_rows($getposts);
				if ($count_note == 0) {
					echo "<p class='nonotefound'>No notes found!</p>";
				}
				
				echo '<ul id="noteshowmore">';
				while ($row = mysql_fetch_assoc($getposts)) {
						include ( "./inc/getProfilepost.inc.php");
						$noteshowmorelastid = $row['id'];
						$profilehm_uname = $row['user_posted_to'];
					}
					if ($count_note >= 4) {
						echo '<li class="noteshowmore" id="'.$noteshowmorelastid.'" >Show More</li>';
					echo '</ul>';
					echo '
					</div>
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
		$('.noteshowmore').live('click',function() {
			var noteshowmorelastid = $(this).attr('id');
			$.ajax({
				type: 'GET',
				url: 'noteshowmore.php',
				data: 'noteshowmorelastid='+noteshowmorelastid,
				beforeSend: function() {
					$('.noteshowmore').html('Loading ...');
				},
				success: function(data) {
					$('.noteshowmore').remove();
					$('#noteshowmore').append(data);
				}
			});
		});
	});
</script>
</body>
</html>
