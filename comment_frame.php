<?php
include ( "./inc/connect.inc.php" );
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: index.php');
}
else {
	$user = $_SESSION['user_login'];
}
?>
<style type="text/css">
hr {
    background-color: #B5B2B2;
    height: 1px;
    margin: 4px 52px;
    border: 0px;
}
.posted_by {
	color: #0B810B; 
	text-decoration: none;
}
.posted_by:hover {
	text-decoration: underline;
}
/* commentBody styel from here*/

.commentPostText {
	margin-left: 7px; 
	font-family: helvetica, arial, sans-serif; 
	font-size: 13px; 
	font-weight: normal; 
	color: #141823; 
	line-height: 1.5;
}
.commentSubmit {
    background-color: #0B810B;
    color: #ECF6EC;
    float: right;
    height: 25px;
    width: 66px;
    font-size: 12px;
    border-radius: 2px;
    border: 1px solid #5C5E5C;
}
.commentSubmit:hover {
  background-color: rgba(11, 129, 11, 0.82);
}
</style>

<script language="javascript">
	function toggle() {
		var ele = document.getElementById("toggleComment");
		var text = document.getElementById("displayComment");
		if (ele.style.display == "block") {
			ele.style.display = "none"
		}else {
			ele.style.display = "block";
		}
	}
</script> 

<?php 

if (isset($_REQUEST['id'])) {
	$getid = $_REQUEST['id'];
}else {
	header('location: index.php');
}
	$post_body = htmlspecialchars(@$_POST['post_body'], ENT_QUOTES);
	$post_body = trim($post_body);
	if ($post_body != "") {
	if (isset($_POST['postComment' . $getid . ''])) {
		$post_body = $_POST['post_body'];
		$date_added = date("Y-m-d");
		$query = mysql_query("SELECT id,added_by  FROM posts WHERE id='$getid'");
		$query_row = mysql_fetch_assoc($query);
		$posted_to = $query_row['added_by'];
		$insertPost = mysql_query("INSERT INTO post_comments VALUES ('','$post_body','$date_added', NOW(), '$user','$posted_to','no','$getid')");
	}
	}
	//post query
	$query = mysql_query("SELECT id,added_by  FROM posts WHERE id='$getid'");
	$query_row = mysql_fetch_assoc($query);
	$posted_to = $query_row['added_by'];
	//getting post by gender
	$postby_query = mysql_query("SELECT * FROM users WHERE username='$posted_to'");
	$postby_gender_row = mysql_fetch_assoc($postby_query);
	$postby_gender_value = $postby_gender_row['gender'];
	//getting user gender
	$usergender_query = mysql_query("SELECT * FROM users WHERE username='$user'");
	$user_gender_row = mysql_fetch_assoc($usergender_query);
	$user_gender_value = $user_gender_row['gender'];
	?>

	<?php 
		if ($postby_gender_value == 2) {
			if (($posted_to == $user) || ($user_gender_value == 2)) {
				echo "
				<div style='margin: 0 52px;'>
				<form action='comment_frame.php?id=".$getid."' method='POST' name='postComment".$getid."'>
					<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='post_body' placeholder= 'Leave your comment here!'>
					<input type='submit' name='postComment".$getid."' class='commentSubmit' value='Comment'>
				</form>
				</div>
			";
		}else {
			$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$posted_to' AND user_to='$user' LIMIT 2");
			$female_msg = mysql_num_rows($get_msg_num);
			if ($female_msg >=1 ) {
				echo "
					<div style='margin: 0 52px;'>
					<form action='comment_frame.php?id=".$getid."' method='POST' name='postComment".$getid."'>
						<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='post_body' placeholder= 'Leave your comment here!'>
						<input type='submit' name='postComment".$getid."' class='commentSubmit' value='Comment'>
					</form>
					</div>
				";
			}else {
				echo "<p style=' text-align: center; font-size: 18px; color: #7B7B7B; font-weight: bold;'>Sorry! You can not leave comment here.</p>";
			}			
		}
		}else {
			echo "
				<div style='margin: 0 7px;'>
				<form action='comment_frame.php?id=".$getid."' method='POST' name='postComment".$getid."'>
					<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='post_body' placeholder= 'Leave your comment here!'>
					<input type='submit' name='postComment".$getid."' class='commentSubmit' value='Comment'>
				</form>
				</div>
			";
		}
	 ?>

	<?php
	//Get relevant comments
	$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$getid' ORDER BY id DESC LIMIT 3");
	$count = mysql_num_rows($get_comments);
	if ($count != 0) {
	while ($comment = mysql_fetch_assoc($get_comments)) {
		$comment_body = $comment['post_body'];
		$date_added = $comment['date_added'];
		$cmntPosted_to = $comment['posted_to'];
		$post_by = $comment['posted_by'];
		$get_user_info = mysql_query("SELECT * FROM users WHERE username='$post_by'");
		$get_info = mysql_fetch_assoc($get_user_info);
		$profile_pic_db= $get_info['profile_pic'];
		$posted_by = $get_info['first_name'];
		
		//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$post_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
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
		
		
			echo "
		<div class='commentPostText'>
		<div style='float: left; margin: 0 10px 0 0;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$posted_by\" height='38' width='38'  /></div>
		<div style='margin-left: 48px;'>
		<b><a href='profile.php?u=$post_by' title=\"Go to $posted_by's Profile\" target='_top' class='posted_by'>$posted_by</a></b><p style='font-size: 10px; margin: 0;'>".$date_added."</p>
		".nl2br($comment_body)."
		</div>
		</div><br>";
		
	}
	}else {
		echo "<center><br><br><br>Opps! There is no comment to view.</center>";
	}

?>