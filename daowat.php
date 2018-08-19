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


<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
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
						<li><a href="daowat.php?u='.$username.'" style="background-color: #cdcdcd; color: #0b810b">Daowat</a></li>
						<li><a href="note.php?u='.$username.'" >Note</a></li>
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

		//daowat update and file check
		$error = "";
		//$daowat = ($_POST['daowat']);
		$daowat = isset($_POST['daowat']) ? $_POST['daowat'] : '';
		$daowat =  trim($daowat);
		$daowat = mysql_real_escape_string($daowat);
		$pic = @$_FILES['uploadFile'];
		if ($pic != "") {
			if (isset($_FILES['uploadFile'])) {
				//finding file extention
				$profile_pic_name = @$_FILES['uploadFile']['name'];
				$file_basename = substr($profile_pic_name, 0, strripos($profile_pic_name, '.'));
				$file_ext = substr($profile_pic_name, strripos($profile_pic_name, '.'));

			if (((@$_FILES['uploadFile']['type']=='image/jpeg') || (@$_FILES['uploadFile']['type']=='image/png') || (@$_FILES['uploadFile']['type']=='image/gif')) && (@$_FILES['uploadFile']['size'] < 200000)) {
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
					$photos = "$chare/$filename";
					$user_posted_to = $user;
					$sqlCommand = "INSERT INTO posts(daowat_body,date_added,added_by,user_posted_to,photos,daowat_give) VALUES('$daowat', '$date_added','$added_by', '$user_posted_to','$photos','1')";
					$query = mysql_query($sqlCommand) or die (mysql_error());
					header("Location: daowat.php?u=$username");
					}
				}else if ($daowat != "") {
					$date_added = date("Y-m-d");
					$added_by = $user;
					$user_posted_to = $user;
					$sqlCommand = "INSERT INTO posts(daowat_body,date_added,added_by,user_posted_to,daowat_give) VALUES('$daowat', '$date_added','$added_by', '$user_posted_to','1')";
					$query = mysql_query($sqlCommand) or die (mysql_error());
					header("Location: daowat.php?u=$username");
				}
				else if ($daowat == "") {
				$error= "<p class='error_echo'>Please write your post!</p>";
				}else {
				$error= "<p class='error_echo'>Invalid File! Your image must be no larger than 200KB.</p>";
				}
			}
		}
		?>
			<div>
			<?php echo $error ?>
				<div style="width: 560px; margin: 15px auto;">
					<?php 
						if ($user == $username) {
							echo "
								<div style='margin: 0;' class='p_postForm' >
								<form action='' method='POST' enctype='multipart/form-data'>
								<div>
								<textarea type='text' id='post' name='daowat' onkeyup='clean('post')' onkeydown='clean('post')' rows='4' cols='58'  class='p_postForm_text' placeholder='Write your daowat...'></textarea>
								<div id='imagePreview'></div>
								</div><br>
								<div>
								<div class='fileupld'>
									<input id='uploadFile' type='file' name='uploadFile' >
								</div>
								<input type='submit' name='uploadpic' value='Daowat' class='p_postSubmit' >
								</div>
								</form>
								</div>
							";
						}else {
							//nothing
						}
					?>
					
					<div class="profilePosts">
					<?php
					//for getting post
					$getposts = mysql_query("SELECT * FROM posts WHERE added_by ='$username' AND (daowat_body !='' || daowat_post != '0') ORDER BY id DESC LIMIT 5 ") or die(mysql_error());
					$count_post = mysql_num_rows($getposts);
					echo '<ul id="daowathmpost">';
						while ($row = mysql_fetch_assoc($getposts)) {
								include ( "./inc/newsfeed.inc.php");
								$daowathmlastid = $row['id'];
							}
							if ($count_post >= 4) {
								echo '<br><li class="getmore" id="'.$daowathmlastid.'" >Show More</li>';
								echo '</ul>';
								echo '
								</div>
								<a href="#top" class="backtotop">⇧</a>
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

					}else {
						header("location: profile.php?u=$username");
						}
					?>


					</div>
					</br>
				</div>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.getmore').live('click',function() {
				var daowathmlastid = $(this).attr('id');
				$.ajax({
					type: 'GET',
					url: 'daowathmmore.php',
					data: 'daowathmlastid='+daowathmlastid,
					beforeSend: function() {
						$('.getmore').html('Loading ...');
					},
					success: function(data) {
						$('.getmore').remove();
						$('#daowathmpost').append(data);
					}
				});
			});
		});
	</script>
</body>
</html>
