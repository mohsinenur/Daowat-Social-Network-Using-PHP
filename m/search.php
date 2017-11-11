<?php include ( "./inc/connect.inc.php" ); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}

//getting user info
$check_user = mysql_query("SELECT * FROM users WHERE username='$user'");
$get_check_user_row = mysql_fetch_assoc($check_user );
$gender_user_db = $get_check_user_row ['gender'];


$photoLocat = 'http://www.daowat.com/';
include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Daowat</title>
	<meta charset="utf-8" />
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
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
	<style type="text/css">
		.c {
			border: 0;
			border-collapse: collapse;
			margin: 0;
			padding: 0;
		}
		.v {
			padding: 6px;
		}
		.c_b {
			color: black;
		}
		.search_banner {
			text-align: center; 
			background-color: rgb(255, 255, 255); 
			padding: 10px;
		}
	</style>
</head>
<body>
	<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>
		<?php echo $error; ?>
		<div class="post_field" style="padding: 10px;">
		<table style="width: 100%;">
			<tbody>
				<form action="search.php" method="get">
					<tr style="margin-right: 10px;">
							<td>
								<div style="text-align: center;">
									<input style="" class='login_unm_pss log_pass1' placeholder='Search Here...' type='text' name='keywords' size='30' required></br>

								</div>
							</td>
					</tr>
					<tr>
					<table style="width: 100%;">
						<td style="float: left;">
						<fieldset>
								<label>
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="topic" value="User" type="radio" checked="checked"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">User</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>
								<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="topic" value="Post" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Post</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>
						</fieldset>
						</td>
						<td style="vertical-align: top;">
							<input type='submit' name='search' value='Search' class='WpostSubmit' >
						</td>
					</table>
					</tr>
				</form>
			</tbody>
		</table>
		</div>
		<div class="search_nav">
			<ul class="search_nav_menu"></ul>
		</div>
		<?php
			if (($_GET['keywords'] && $_GET['topic']) != NULL) {
				$search_value = "";
				$count = "";
				if (isset($_GET['keywords'])) {
					if ($_GET['topic'] == "User") {
						$search_value = $_GET['keywords'];
						$search_value = trim($search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {
					$search_for = $search_value;
					if ( $gender_user_db == '1') {
					$query = "SELECT id,username,first_name,profile_pic,cover_pic,city,hometown,company,school,gender,verify_id FROM users where (username like '%$search_value%' OR first_name like '%$search_value%') AND (gender !='2' AND activated !='0')";
					}else if ( $gender_user_db == '2') {
					$query = "SELECT id,username,first_name,profile_pic,cover_pic,city,hometown,company,school,gender,verify_id FROM users where (username like '%$search_value%' OR first_name like '%$search_value%') AND (activated !='0')";
					}
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner" >No match found!
					</div>';
				}else {
					echo '<div style="padding: 10px;"><div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
						<div class="search_result_container">
							';
						while ($row=mysql_fetch_array($query)) {
							$id = $row['id'];
							$username = $row['username'];
							$first_name = $row['first_name'];
							$city = $row['city'];
							$hometown = $row['hometown'];
							$company = $row['company'];
							$school = $row['school'];
							$profile_pic_db = $row['profile_pic'];
							$cover_pic_db = $row['cover_pic'];
							//check for propic delete
							$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$username' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
							$get_pro_changed = mysql_fetch_assoc($pro_changed);
							$pro_num = mysql_num_rows($pro_changed);
							if ($pro_num == 0) {
								$profile_pic = "".$photoLocat."img/default_propic.png";
							}else {
								$pro_changed_db = $get_pro_changed['photos'];
							if ($pro_changed_db != $profile_pic_db) {
								$profile_pic = "".$photoLocat."img/default_propic.png";
							}else {
								$profile_pic = "".$photoLocat."userdata/profile_pics/".$profile_pic_db;
							}
							}
							$verify_id_user = $row['verify_id'];
	
							echo "
							<div style=' padding: 8px 0; min-height: 80px;'> ";
									echo "<div style='float: left;'>
									<img src='$profile_pic' style= 'border-radius: 4px' border: 1px solid #ddd; title=\"$first_name\" height='70' width='65'  />
									</div>";
								
								echo "<div style='margin-left: 74px; line-height: 18px; font-size: 14px;'><b><a href='profile.php?u=".$username."' style='text-decoration: none; font-size: 14px; color: #0B810B;' title=\"Go to ".$first_name."'s Profile\" class='posted_by'>".$first_name."</a></b> <br>";
								if ($user == $username) {
									echo '<div class="">
									<form action="about.php?u='.$username.'" method="POST">
									<button value="button" class="cancelSubmit" name="updateProfile" >Edit profile</button />
									</form>
									</div>';
								}else {
									echo '<div class="" style="float: left;">
									<form action="message.php?u='.$username.'" method="POST">
										<input input type="submit" class="cancelSubmit" name="sendmsg" value="Message" />
									</form>
									</div>
									<div class="coll3" style="">
									<form action="profile.php?u='.$username.'" method="POST">
										<button value="button" class="cancelSubmit" name="viewProfile" >View profile</button />
									</form>
									</div>';
								}
								if ($school == "") {
										if ($company == "") {
										//nothing
									}else {
										echo 'Work at <span style="color: #0B810B;">'.$company.'</span><br>' ;
									}
									}else {
										echo 'Study at <span style="color: #0B810B;">'.$school.'</span><br>' ;
									}if ($city == "") {
											if ($hometown == "") {
											//nothing
										}else {
											echo 'From <span style="color: #0B810B;">'.$hometown.'</span>' ;
										}
									}else {
										echo 'Lives in <span style="color: #0B810B;">'.$city.'</span><br>' ;
									}
								echo "</div>
							
							</div>
							";
						
							}
						echo"</div>";
						}
					}
				}else if ($_GET['topic'] == "Post") {
					$search_value = $_GET['keywords'];
					$search_value = trim($search_value);
					$search_value = preg_replace('/[^\p{L}0-9\s]+/u', '-', $search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {
	
					$search_for = $search_value;
					$query = "SELECT id,body,date_added,added_by,photos,user_posted_to,discription FROM posts where  (body like '%$search_value%')  AND (note_privacy !='onlyme') ORDER BY id DESC";
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner">No match found!
					</div>';
				}else {
					echo '<div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
						<div class="search_result_container">
							';
						while ($row=mysql_fetch_array($query)) {
								include ( "./inc/getProfilepost.inc.php" );
							}
						}
					}
				}else if ($_GET['topic'] == "Daowat") {
					$search_value = $_GET['keywords'];
					$search_value = trim($search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {
					$search_for = $search_value;
					$query = "SELECT id,body,date_added,added_by,photos FROM daowat where body like '%$search_value%' ORDER BY id DESC";
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner">No match found!
					</div>';
				}else {
					echo '<div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
						<div class="profilePosts search_result_container">
							';
						while ($row=mysql_fetch_array($query)) {
								include ( "./inc/getDaowatpost.inc.php" );
							}
						}
					}
				}
			}
		}
			
	?>

</div>
</body>
</html>