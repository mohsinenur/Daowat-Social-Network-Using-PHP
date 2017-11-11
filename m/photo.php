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
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>
			<?php include ( "./inc/profile.inc.php"); 
				echo '
					<div class="pro_header"> 
						<nav class="pro_hdr_menu">
							<ul>
								<li><a href="profile.php?u='.$username.'">Post</a></li>
								<li><a href="about.php?u='.$username.'">About</a></li>
								<li><a href="friends.php?u='.$username.'">Friend</a></li>
								<li style="border-bottom: 4px solid #2AED25; color: #2AED25;"><a href="photo.php?u='.$username.'">Photo</a></li>
								<li><a href="note.php?u='.$username.'">Note</a></li>
							</ul>
						</nav>
				</div>
			';
			?>
			<div style="margin: 10px; min-height: 275px; height: auto; position: absolute;">
			<div style="text-align: center;">
				<?php 

						$getposts = mysql_query("SELECT * FROM posts WHERE user_posted_to ='$username' AND note='0' ORDER BY id DESC ") or die(mysql_error());
						while ($row = mysql_fetch_assoc($getposts)) {
							$id = $row['id'];
							$body = $row['body'];
							$date_added = $row['date_added'];
							$added_by = $row['added_by'];
							$discription = $row['discription'];
							$photos_db = $row['photos'];
							$photos = "http://www.daowat.com/userdata/profile_pics/".$photos_db;
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
											<div style='border: 5px solid #FFFFFF; '>
												<div class='prflloadPhoto'>
													<img src=".$photos." style='height: 78px; width: 78px;'/>
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
	</div>
</body>
</html>