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

//post update
$error = "";
$post = isset($_POST['post']) ? $_POST['post'] : '';
$post =  trim($post);
$post = mysql_real_escape_string($post);
$pic = @$_FILES['uploadFile'];
if ($pic != "") {
	if (isset($_FILES['uploadFile'])) {
		//finding file extention
		$profile_pic_name = @$_FILES['uploadFile']['name'];
		$file_basename = substr($profile_pic_name, 0, strripos($profile_pic_name, '.'));
		$file_ext = substr($profile_pic_name, strripos($profile_pic_name, '.'));

	if (((@$_FILES['uploadFile']['type']=='image/jpeg') || (@$_FILES['uploadFile']['type']=='image/png') || (@$_FILES['uploadFile']['type']=='image/gif')) && (@$_FILES['uploadFile']['size'] < 5000000)) {
		$chare = $user;
		if (file_exists("userdata/profile_pics/$chare")) {
			//nothing
		}else {
			mkdir("userdata/profile_pics/$chare");
		}
		
		
		$filename = strtotime(date('Y-m-d H:i:s')) . $file_ext;

		if (file_exists("userdata/profile_pics/$chare/".@$_FILES["uploadFile"]["name"])) {
			echo @$_FILES["uploadFile"]["name"]."Already exists";
		}else {
			move_uploaded_file(@$_FILES["uploadFile"]["tmp_name"], "userdata/profile_pics/$chare/".$filename);
			//echo "Uploaded and stored in: userdata/profile_pics/$chare/".@$_FILES["uploadFile"]["name"];
			
			$date_added = date("Y-m-d");
			$added_by = $user;
			$user_posted_to = $username;
			$discription = "added a new photo.";
			$photos = "$chare/$filename";
			if ($username == $user) {
				$newsfeedshow = '1';
			}else {
				$newsfeedshow = '0';
			}
			$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to,photos,newsfeedshow ) VALUES('$post', '$date_added','$added_by', '$user_posted_to', '$photos', '$newsfeedshow')";
			$query = mysql_query($sqlCommand) or die (mysql_error());
			header("Location: profile.php?u=$username");
			}
		}
		else {
		$error= "<p class='error_echo'>Invalid File! Your image must be no larger than 5MB and it must be either a .jpg, .jpeg, .png or .gif</p>";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript">
		$(function() {
		    $("#uploadFile").on("change", function()
		    {
		        var files = !!this.files ? this.files : [];
		        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
		        
		        if (/^image/.test( files[0].type)){ // only image file
		            var reader = new FileReader(); // instance of the FileReader
		            reader.readAsDataURL(files[0]); // read the local file
		            
		            reader.onloadend = function(){ // set image data as background of div
		                $("#imagePreview").css("background-image", "url("+this.result+")");
		            }
		        }
		    });
		});
	</script>
</head>
<body>
	<main>
		<article>
			<?php include ( "./inc/header.inc.php"); ?>
			<?php include ( "./inc/profile.inc.php"); ?>
			<?php echo '<li style="float: right;">
							
							
					<div >
						<nav>
						<ul>
						<li><a href="daowat.php?u='.$username.'">Daowat</a></li>
						<li><a href="note.php?u='.$username.'">Note</a></li>
						<li><a href="photo.php?u='.$username.'" style="background-color: #cdcdcd; color: #0b810b">Photo</a></li>
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
	</div>'; ?>
			<?php echo $error; ?>
			<div style="max-width: 920px; margin: 0 auto;">
				<?php 
					$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
					$msg_count = mysql_num_rows($get_msg_num);
					if (($msg_count >=1 ) || ($username == $user)) {
						echo '
							<div class="p_postForm ">
							<form action="" method="POST" enctype="multipart/form-data">
								<div >
								<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" rows="4" cols="58"  class="p_postForm_text" placeholder="What on your mind..."></textarea>
								<div id="imagePreview"></div>
								</div><br>
								<div>
								<div class="fileupld">
									<input id="uploadFile" type="file" name="uploadFile" >
								</div>
								<input type="submit" name="uploadpic" value="Upload" class="p_postSubmit" >
								</div>
							</form>
						</div>
						';
					}else {
						//nothing
					}
				 ?>
				
				<div style="">
					<?php 

						$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' AND note='0' ORDER BY id DESC ") or die(mysql_error());
						while ($row = mysql_fetch_assoc($getposts)) {
							$id = $row['id'];
							$body = $row['body'];
							$date_added = $row['date_added'];
							$added_by = $row['added_by'];
							$discription = $row['discription'];
							$photos_db = $row['photos'];
							$photos = "./userdata/profile_pics/".$photos_db;
							$get_user_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
							$get_info = mysql_fetch_assoc($get_user_info);
							$profilepic_info = $get_info['profile_pic'];
					
							if ($photos_db == "") {
								//no echo here please!
								echo "";
							}else {
								echo "
									<div class='photoupload'>
										<a href='viewPost.php?pid=".$id."' >
											<div style='border: 15px solid #FFFFFF; '>
												<div style='background: url($photos) repeat center center; margin: 0px; height: 270px; width: 270px; background-size: cover !important;'>
												</div>
											</div>
										</a>
									</div>
								";
							}
						}
					?>
				</div>
			</div>
		</article>
	</main>

</body>
</html>
