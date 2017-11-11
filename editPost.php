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
	$id = $_REQUEST['pid'];
}else {
	header('location: index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Post • Daowat</title>
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
<?php 

include ( "./inc/header.inc.php");
echo "
<div style='max-width: 960px; margin: 0 auto;'>
	<div class='profilePosts' style= 'margin: 55px auto';>";
	
//for getting post
$getposts = mysql_query("SELECT * FROM posts WHERE id ='$id'") or die(mysql_error());
while ($row = mysql_fetch_assoc($getposts)) {
		$id = $row['id'];
		$body = $row['body'];
		$date_added = $row['date_added'];
		$added_by = $row['added_by'];
		$user_posted_to = $row['user_posted_to'];
		$discription = $row['discription'];
		$photos_db = $row['photos'];
		$photos = "./userdata/profile_pics/".$photos_db;
		$get_posted_to_info = mysql_query("SELECT * FROM users WHERE username='$user_posted_to'");
		$get_posted_info = mysql_fetch_assoc($get_posted_to_info);
		$posted_to_fname = $get_posted_info['first_name'];
		$get_user_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
		$get_info = mysql_fetch_assoc($get_user_info);
		$profilepic_info = $get_info['profile_pic'];
		$add_by_fname = $get_info['first_name'];

		//Get Relevant Comments
		$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
		$comment = mysql_fetch_assoc($get_comments);
		$comment_body = $comment['post_body'];
		$posted_to = $comment['posted_to'];
		$posted_by = $comment['posted_by'];
		$removed = $comment['post_removed'];
		?>

		<?php
		//count comment
		$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
		$count = mysql_num_rows($get_comments);
		//showing data on profile
			if ($profilepic_info == "") {
				if ($user_posted_to == $added_by) {
				echo "<div class='postBody post_search_result_box'>
						<div style='float: left; margin-left: 10px;'><img src='img/default_propic.png' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>
						<div class='posted_by'><a href='$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />
						<div class='posted_date'>$date_added</div> <br /> 
						<div class='posted_body'>".nl2br($body)."<br> ";
						if ($photos_db == "") {
							//nothing
						}else {
							echo "<img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/>";
						}
						echo "
						</div>
						<br /><hr />
						<div class='likeComShare'>
						<a href='#' >Like</a>
						<a href='javascript:;' onClick='javascript:toggle$id()'>Comments ($count)</a>
						<a href='#' >Share</a>
						<a href='editPost.php?pid=".$id."' >More</a>
						</div>
					</div>
					<div class='commentBodyview'>
					<br />
					<iframe src='./comment_frame.php?id=$id' frameborder='0'></iframe>
					</div> <br />";
			}else {
				echo "<div class='postBody post_search_result_box'>
						<div style='float: left; margin-left: 10px;'><img src='img/default_propic.png' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>
						<div class='posted_by'><a href='$added_by' title=\"Go to $added_by's Profile\">$add_by_fname &nbsp;&nbsp;</a><h style='font-size: 13px; color: #656262;'>>></h><a href='$user_posted_to' title=\"Go to $user_posted_to's Profile\">$posted_to_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />
						<div class='posted_date'>$date_added</div> <br /> 
						<div class='posted_body'>".nl2br($body)."<br> ";
						if ($photos_db == "") {
							//nothing
						}else {
							echo "<img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/>";
						}
						echo "
						</div>
						<br /><hr />
						<div class='likeComShare'>
						<a href='#' >Like</a>
						<a onClick='javascript:toggle$id()'>Comments ($count)</a>
						<a href='#' >Share</a>
						<a href='editPost.php?pid=".$id."' >More</a>
						</div>
					</div>
					<div class='commentBodyview'>
					<br />
					<iframe src='./comment_frame.php?id=$id' frameborder='0'></iframe>
					</div> <br />";
			}
			}else {
				if ($user_posted_to == $added_by) {
				echo "<div class='postBody post_search_result_box'>
						<div style='float: left; margin-left: 10px;'><img src='userdata/profile_pics/$profilepic_info' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>
						<div class='posted_by'><a href='$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />
						<div class='posted_date'>$date_added</div> <br /> 
						<div class='posted_body'>".nl2br($body)."<br> ";
						if ($photos_db == "") {
							//nothing
						}else {
							echo "<img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/>";
						}
						echo "
						</div>
						<br /><hr />
						<div class='likeComShare'>
						<a href='#' >Like</a>
						<a onClick='javascript:toggle$id()'>Comments ($count)</a>
						<a href='#' >Share</a>
						<a href='editPost.php?pid=".$id."' >More</a>
						</div>
					</div> 
					<div class='commentBodyview'>
					<br />
					<iframe src='./comment_frame.php?id=$id 'frameborder='0'></iframe>
					</div> <br />";
				}else {
				echo "<div class='postBody post_search_result_box'>
						<div style='float: left; margin-left: 10px;'><img src='userdata/profile_pics/$profilepic_info' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>
						<div class='posted_by'><a href='$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a> &nbsp;&nbsp;</a><h style='font-size: 13px; color: #656262;'>>></h><a href='$user_posted_to' title=\"Go to $user_posted_to's Profile\">$posted_to_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />
						<div class='posted_date'>$date_added</div> <br /> 
						<div class='posted_body'>".nl2br($body)."<br> ";
						if ($photos_db == "") {
							//nothing
						}else {
							echo "<img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/>";
						}
						echo "
						</div>
						<br /><hr />
						<div class='likeComShare'>
						<a href='#' >Like</a>
						<a onClick='javascript:toggle$id()'>Comments ($count)</a>
						<a href='#' >Share</a>
						<a href='editPost.php?pid=".$id."' >More</a>
						</div>
					</div>
					<div class='commentBodyview'>
					<br />
					<iframe src='./comment_frame.php?id=$id 'frameborder='0'></iframe>
					</div> <br />";
				}
			}
		}
 ?>
 </div>
 </div>
</body>
</html>