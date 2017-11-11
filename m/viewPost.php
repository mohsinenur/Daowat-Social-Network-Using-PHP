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

if (isset($_REQUEST['pid'])) {
	$id = mysql_real_escape_string($_REQUEST['pid']);
}else if (isset($_REQUEST['did'])) {
	$id = mysql_real_escape_string($_REQUEST['did']);
}else {
	header('location: index.php');
}


include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

?>
<!DOCTYPE html>
<html>
<head>
	<title>View Post - Daowat</title>
	<meta charset="utf-8" />
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript">
	function confirm_delete() {
		return confirm('Are you sure want to delete this?');
	}
	</script>
	<script type="text/javascript">
	function confirm_report() {
		return confirm('Are you sure want to report this?');
	}
	</script>
</head>
<body>
<div class="pro_body">
<div class="pro_header">
	<nav class="pro_hdr_menu">
		<?php include ( "./inc/hdrmenu.inc.php"); ?>
	</nav>
</div>
<?php 


echo "
	<div class='profilePosts' style= 'margin: 0 auto';>";
	
//for getting post
$getposts = mysql_query("SELECT * FROM posts WHERE id ='$id'") or die(mysql_error());
$getposts_num = mysql_num_rows($getposts );
if ($getposts_num == 0) {
	header('location: newsfeed.php');
}else {

while ($row = mysql_fetch_assoc($getposts)) {
		$id = $row['id'];
		$body = $row['body'];
		$dwt_body = $row['daowat_body'];
		$date_added = $row['date_added'];
		$added_by = $row['added_by'];
		$user_posted_to = $row['user_posted_to'];
		if (isset($_REQUEST['pid'])) {
			$sharepostid = $row['share_post'];
			
		}else if (isset($_REQUEST['did'])) {
			$sharepostid = $row['daowat_post'];
		}
		$daowat_give = $row['daowat_give'];
		$discription = $row['discription'];
		$photos_db = $row['photos'];
		$photos = "http://www.daowat.com/userdata/profile_pics/".$photos_db;
		$get_posted_to_info = mysql_query("SELECT * FROM users WHERE username='$user_posted_to'");
		$get_posted_info = mysql_fetch_assoc($get_posted_to_info);
		$posted_to_fname = $get_posted_info['first_name'];
		$get_user_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
		$get_info = mysql_fetch_assoc($get_user_info);
		$profile_pic_db= $get_info['profile_pic'];
		$add_by_fname = $get_info['first_name'];
		$gender_user_db = $get_info['gender'];
		if($daowat_give != 0) {
			$body = $dwt_body;
		}
		//share post info
		$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$sharepostid'");
		$post_info = mysql_fetch_assoc($get_sharepost );
		$sp_id = $post_info['id'];
		$sp_added_by = $post_info['added_by'];
		$sp_get_user_info = mysql_query("SELECT * FROM users WHERE username='$sp_added_by'");
		$sp_get_info = mysql_fetch_assoc($sp_get_user_info);
		$sp_add_by = $sp_get_info['first_name'];

		
			//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$added_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_pic = "http://www.daowat.com/img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db) {
			$profile_pic = "http://www.daowat.com/img/default_propic.png";
		}else {
			$profile_pic = "http://www.daowat.com/userdata/profile_pics/".$profile_pic_db;
		}
		}

		//Get Relevant Comments
		$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
		$comment = mysql_fetch_assoc($get_comments);
		$comment_body = $comment['post_body'];
		$posted_to = $comment['posted_to'];
		$posted_by = $comment['posted_by'];
		?>

		<?php
		//share post discription
		if($sharepostid != 0) {
			if($sp_added_by == $added_by) {
				if ($gender_user_db == '1') {
					$discription = "shared his <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>post</a>";
				}else if ($gender_user_db == '2') {
					$discription = "shared her <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>post</a>";
				}
			}else {
				$discription = "shared ".$sp_add_by." <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>post</a>";
			}
		}
		
		//count comment
		$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
		$count = mysql_num_rows($get_comments);
		//getting all like
		$get_like = mysql_query("SELECT * FROM post_likes WHERE post_id='$id' ORDER BY id DESC");
		$count_like = mysql_num_rows($get_like);
		//showing data on profile
				echo "<div class='postBody post_search_result_box'>";
				
					if ($user_posted_to == $added_by) {
							echo "<div style='margin-left: 10px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div>";
							echo "<div class='posted_date'>$date_added</div></div>";
					}else {
							echo "<div style='margin-left: 10px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $added_by's Profile\">$add_by_fname &nbsp;&nbsp;</a><h style='font-size: 13px; color: #656262;'>>></h><a href='profile.php?u=$user_posted_to' title=\"Go to $user_posted_to's Profile\">$posted_to_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div>";
							echo "<div class='posted_date'>$date_added</div></div>";
					}
						echo "<div class='posted_body' style='padding: 2px 13px 0px 10px;'>".nl2br($body)." ";

						if ($photos_db == "") {
						}else {
							echo "<a href='viewPost.php?pid=".$id."'><img src='$photos' style='max-width: 420px; width: 100%; border: 1px solid #ddd;'/></a>";
						}

						echo "
						</div>";
						
						if($sharepostid != 0) {
						//share post info
						$sp_profile_pic_db= $sp_get_info['profile_pic'];
						$sp_body = $post_info['body'];
						$sp_dwtbody = $post_info['daowat_body'];
						$sp_discription = $post_info['discription'];
						$sp_date_added = $post_info['date_added'];
						$sp_dwtgive = $post_info['daowat_give'];
						$sp_photos_db = $post_info['photos'];
						if($sp_dwtgive != 0) {
							$sp_body = $sp_dwtbody;
						}
						$sp_photos = "http://www.daowat.com/userdata/profile_pics/".$sp_photos_db;
					
							
							echo'
							<div style="border: 1px solid; border-color: #e9eaed #e9eaed #d1d1d1; padding: 6px 6px 0px; margin: 0px 10px;">';
							
								echo "<div class='posted_by' style='font-size: 12px;'><a href='profile.php?u=$sp_added_by' title=\"Go to $sp_add_by's Profile\">$sp_add_by</a> <span style='color: #9E9E9E; font-weight: normal;'>$sp_discription</span></div>";
								echo "<div class='posted_body' style='font-size: 12px;color: #666;'><div class='posted_date'>$sp_date_added</div><p>".nl2br($sp_body)."</p>";
									if ($sp_photos_db == "") {
										//nothing
									}else {
										echo "<a href='viewPost.php?pid=".$sp_id."'><img src='$sp_photos' style='max-width: 420px; width: 100%; margin-top: 5px; border: 1px solid #ddd;'/></a>";
									}
							
							echo'</div></div>';
							
						}
						
						
						echo "<br />";
						
						echo "<div class='likeComShare'>";
						$like_query = mysql_query("SELECT * FROM post_likes WHERE user_name='$user' AND post_id='$id' ORDER BY id DESC LIMIT 1");
						$rows_uname = mysql_num_rows($like_query);
						if ($rows_uname == 1) {
							echo "<a href='like.php?upid=".$id."' style='color: #0B810B;' >Liked</a>";
							echo "<a href='plikesppl.php?plikep=".$id."' style='color: #0B810B;' >($count_like)</a>";
						}else {
							echo "<a href='like.php?pid=".$id."' >Like ($count_like)</a>";
						}
						echo "<a href='viewPost.php?pid=".$id."'>Comments ($count)</a>
						<a href='moreoptions.php?pid=".$id."' >More</a>";
						
						echo "</div>
					</div>
					<div class='commentBodyview'>
					<iframe style='width: 100%; height: auto; min-height: 300px;' src='./comment_frame.php?id=$id' frameborder='0'></iframe>
					</div>";
		}
		
	}
 ?>
 </div>

 </div>
 <div>
			<?php include("./inc/footer.inc.php") ?>
		</div>
</body>
</html>