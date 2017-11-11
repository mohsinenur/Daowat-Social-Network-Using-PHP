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

if (isset($_REQUEST['pid'])) {
	
	$id = mysql_real_escape_string($_REQUEST['pid']);
}else {
	header('location: index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>View Post . Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
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
<?php 

include ( "./inc/header.inc.php");
echo "
<div style='max-width: 960px; margin: 0 auto;'>
	<div class='profilePosts' style= 'margin: 55px auto';>";
	
//for getting post
$getposts = mysql_query("SELECT * FROM posts WHERE id ='$id'") or die(mysql_error());
$getposts_num = mysql_num_rows($getposts );
if ($getposts_num == 0) {
	header('location: newsfeed.php');
}else {

$row = mysql_fetch_assoc($getposts);
$id = $row['id'];
$body = $row['body'];
$dwt_body = $row['daowat_body'];
$date_added = formatDate($row['date_added']);
$added_by = $row['added_by'];
$user_posted_to = $row['user_posted_to'];
$discription = $row['discription'];
$photos_db = $row['photos'];
$sharepostid = $row['share_post'];
$daowatpostid = $row['daowat_post'];
$daowat_give = $row['daowat_give'];
$photos = "./userdata/profile_pics/".$photos_db;
$get_user_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
$get_info = mysql_fetch_assoc($get_user_info);
$profile_pic_db= $get_info['profile_pic'];
$gender_user_db = $get_info['gender'];
$add_by = $get_info['first_name'];
$get_fname_info = mysql_query("SELECT * FROM users WHERE username='$user_posted_to'");
$get_fname_info = mysql_fetch_assoc($get_fname_info);
$post_to_fname = $get_fname_info['first_name'];
//share post info
if($sharepostid == 0) {
	$sharepostid = $daowatpostid;	
}
if($daowat_give != 0) {
	$body = $dwt_body;
}
$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$sharepostid'");
$post_info = mysql_fetch_assoc($get_sharepost );
$sp_id = $post_info['id'];
$sp_added_by = $post_info['added_by'];
$sp_added_photo = $post_info['photos'];
$sp_photos = "./userdata/profile_pics/".$sp_added_photo;
$sp_get_user_info = mysql_query("SELECT * FROM users WHERE username='$sp_added_by'");
$sp_get_info = mysql_fetch_assoc($sp_get_user_info);
$sp_profile_pic_db= $sp_get_info['profile_pic'];
$sp_add_by = $sp_get_info['first_name'];

		//check for propic delete
					$sp_pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$sp_added_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
					$sp_get_pro_changed = mysql_fetch_assoc($sp_pro_changed);
	$sp_pro_num = mysql_num_rows($sp_pro_changed);
	if ($sp_pro_num == 0) {
		$sp_profile_pic = "img/default_propic.png";
	}else {
		$sp_pro_changed_db = $sp_get_pro_changed['photos'];
	if ($sp_pro_changed_db != $sp_profile_pic_db) {
		$sp_profile_pic = "img/default_propic.png";
	}else {
		$sp_profile_pic = "userdata/profile_pics/".$sp_profile_pic_db;
	}
	}
	
	//check for propic delete
					$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$added_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
					$get_pro_changed = mysql_fetch_assoc($pro_changed);
	$pro_num = mysql_num_rows($pro_changed);
	if ($pro_num == 0) {
		$profile_pic = "img/default_propic.png";
	}else {
		$pro_changed_db = $get_pro_changed['photos'];
	if ($pro_changed_db != $profile_pic_db) {
		$profile_pic = "img/default_propic.png";
	}else {
		$profile_pic = "userdata/profile_pics/".$profile_pic_db;
	}
	}
	
	
//share post discription
if($sharepostid != 0) {
	$post_item = "";
	if($sp_added_photo == "") {
		$post_item = "post";
	}else {
		$post_item = "photo";
	}
	if($sp_added_by == $added_by) {
		if ($gender_user_db == '1') {
			$discription = "shared his <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; padding-left: 0px; color: #0B810B;'>".$post_item."</a>";
		}else if ($gender_user_db == '2') {
			$discription = "shared her <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>".$post_item."</a>";
		}
	}else {
		$discription = "shared ".$sp_add_by."'s <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; padding-left: 0px; color: #0B810B;'>".$post_item."</a>";
	}
}

//Get Relevant Comments
$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC LIMIT 3");
$comment = mysql_fetch_assoc($get_comments);
$comment_body = $comment['post_body'];
$posted_to = $comment['posted_to'];
$posted_by = $comment['posted_by'];
?>
<script language="javascript">
function toggle<?php echo $id; ?>() {
	var ele = document.getElementById("toggleComment<?php echo $id; ?>");
	var text = document.getElementById("displayComment<?php echo $id; ?>");
	if (ele.style.display == "block") {
		ele.style.display = "none"
	}else {
		ele.style.display = "block";
		
	}
}
</script>

<?php
//count comment
$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC ");
$count = mysql_num_rows($get_comments);
//getting all like
$get_like = mysql_query("SELECT * FROM post_likes WHERE post_id='$id' ORDER BY id DESC");
$count_like = mysql_num_rows($get_like);
//showing data on profile
		echo "<div class='postBody' >";
		
		echo "<div style='min-height: 55px;' ><div style='float: left;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>";
		
			echo'<div class="posted_by">
				<span style="color: #9e9e9e;" >
					<span style="font-weight: bold;">';
					if($user_posted_to == $added_by) {
					echo'<a href="profile.php?u='.$added_by.'" style="text-decoration: none; color: #0B810B;">'.$add_by.'</a><span> '.$discription.'</span>';
					}else {
					echo'<a href="profile.php?u='.$sp_added_by.'" style="text-decoration: none; color: #0B810B;">'.$add_by.'</a> > <a href="profile.php?u='.$user_posted_to.'" style="text-decoration: none; color: #0B810B;">'.$post_to_fname.'</a>';
					}
						
					echo'</span>
				</span>
				<div>
					<span>
							
						<span style="color: #585858; font-size: 10px;" >'.$date_added.'</span>
							
					</span>
				</div></div>
				<div>
					<p style="line-height: 1.5; font-size: 16px;" >'.nl2br($body).'</p>';
					if($photos_db != NULL) {
						echo'<div>
						<a href="viewPost.php?pid='.$id.'" ><img src="'.$photos.'" style=" max-width: 530px; width: 100%; margin-top: 5px; border: 1px solid #ddd;"  /></a>
					</div>';
					}
				echo'</div>
			</div>';
			
			if($sharepostid != 0) {
				
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
					
					echo'<div style="border: 1px solid #cdcdcd;">';
					if($sp_added_photo != NULL) {
									echo'<a href="viewPost.php?pid='.$sp_id.'" ><img src="'.$sp_photos.'" style="width: 100%; border-bottom: 1px solid #ddd;"  /></a>';
								}
					
					echo'
					<div style="padding: 10px;" >';
					
						echo'<div>
							<a href="profile.php?u='.$sp_added_by.'" class="" style="float: left;" ><img src="'.$sp_profile_pic.'" style="height: 32px; width: 32px; border: 1px solid #ddd; border-radius: 5px;"  /></a>
						</div>
						<div style=" padding-left: 10px; overflow: hidden; ">
							<span style="color: #9197a3;" >
								<span style="font-weight: bold;">
									<a href="profile.php?u='.$sp_added_by.'" style="text-decoration: none; color: #0B810B;">'.$sp_add_by.'</a>
								</span>
							</span>
							<div style="line-height: 1.5;">
								<span>
										
									<span style="color: #585858; font-size: 10px;" >'.$date_added.'</span>
										
								</span>
							</div>
							<div>
								<p style="color: #9E9E9E; line-height: 1.5; font-size: 14px;" >'.$sp_body.'</p>';
								
							echo'</div>
						</div>
					</div>
					</div>';
					
				}
			
			echo "
			<br /><hr style='margin: 0px 0px 10px 0px;' />
			<div class='likeComShare'>";
			$like_query = mysql_query("SELECT * FROM post_likes WHERE user_name='$user' AND post_id='$id' ORDER BY id DESC LIMIT 1");
			$rows_uname = mysql_num_rows($like_query);
			if ($rows_uname == 1) {
				echo "<a href='like.php?upid=".$id."' style='color: #0B810B;' >Liked . $count_like</a>";
			}else {
				echo "<a href='like.php?pid=".$id."' >Like . $count_like</a>";
			}
			echo "<a href='javascript:;' onClick='javascript:toggle$id()'>Comments ($count)</a>";
			if(($added_by == $user) || ($user_posted_to == $user)) {
				echo"<a href='deletePost.php?pid=".$id."' >Delete</a>";
			}else {
				echo"<a href='' >Report</a>";
			}
			echo"</div>
		</div>
		<div id='toggleComment$id' class='commentBody' style='display: block;'>
		<br />
		<iframe src='./comment_frame.php?id=$id' frameborder='0'></iframe>
		</div> <br />";
}
 ?>
 </div>
 </div>
</body>
</html>