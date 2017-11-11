<?php 
include ( "./inc/connect.inc.php");

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
	$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$post_info = mysql_fetch_assoc($get_sharepost);
	$sp_id = $post_info['id'];
	$sharepostid = $post_info['share_post'];
	$sharedaowatid = $post_info['daowat_post'];
	
	
  }else {
	header('location: index.php');
}


if (isset($_POST['continue']) and ! empty($_POST['continue'])) {
    if (isset($_POST['action_key'])) {
        $radio_input = $_POST['action_key'];
        if($radio_input == "DELETE_POST") {
        	$get_file = mysql_query("SELECT * FROM posts WHERE id='$id'");
		$get_file_name = mysql_fetch_assoc($get_file);
		$db_filename = $get_file_name['photos'];
        	$delete_file = unlink("../userdata/profile_pics/".$db_filename);
		//delete all like
		$resultdl = mysql_query("DELETE FROM post_likes WHERE post_id='$id'");
		//delete share post comment like
		$getshareposts = mysql_query("SELECT * FROM posts WHERE share_post='$id'") or die(mysql_error());
		while ($row_share = mysql_fetch_assoc($getshareposts)) {
		$spostid= $row_share['id'];
		$resultdlt = mysql_query("DELETE FROM post_comments WHERE post_id='$spostid'");
		$resultdl = mysql_query("DELETE FROM post_likes WHERE post_id='$spostid'");
		}
		//delete all comments
		$resultd2 = mysql_query("DELETE FROM post_comments WHERE post_id='$id'");
		//delete share post
		$result1 = mysql_query("DELETE FROM posts WHERE share_post='$id'");
		//delete post
		$result3 = mysql_query("DELETE FROM posts WHERE id='$id'");
		header("location: profile.php?u=$user");
        }else if($radio_input == "EDIT_POST") {
        	$_SESSION['postid'] = $id;
        	header("location: editpost.php?pid=$id");
        }else if($radio_input == "REPORT_POST") {
        	$id = $_REQUEST['pid'];

		//reporting post
		$result = mysql_query("UPDATE posts SET report='1' WHERE id='$id'");
		header("location: newsfeed.php");
        }else if($radio_input == "SHARE_POST") {
        	$_SESSION['postid'] = $id;
		
		if($sharepostid != 0) {
			$_SESSION['postid'] = $sp_id;
			header("location: sharepost.php?pid=$sharepostid");
		}else {
			header("location: sharepost.php?pid=$id");
		}
        	
        }else if($radio_input == "DAOWAT_POST") {
        	$_SESSION['daowatid'] = $id;
		
		if($sharedaowatid != 0) {
			$_SESSION['daowatid'] = $sp_id;
			header("location: sharepost.php?did=$sharedaowatid");
		}else {
			header("location: sharepost.php?did=$id");
		}
        	
        }else {
        	header('location: index.php');
        }
    }
} else {

}

if(isset($_POST['cancel'])) {
	header('location: newsfeed.php');
}

?>




<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		.c {
			border: 0;
			border-collapse: collapse;
			margin: 0;
			padding: 0;
		}
		.c tbody {
			vertical-align: top;
		}
		.radio {
			vertical-align: middle;
		}
		.v {
			padding: 6px;
		}
		.lb {
			border-bottom: 1px solid #e5e5e5; 
			display: block;
		}
		.c_g {
			color: gray;
		}
		.c_b {
			color: black;
		}
		.cc {
			display: inline-block;
			margin: 6px 0 6px 6px;
		}
		.vp {
			text-decoration: none;
			color: #0b810b;
			font-size: 16px;
		}
	</style>
	<title>More Option</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="pro_body">
	<div class="pro_header">
		<nav class="pro_hdr_menu">
			<ul>
				<li><a href="index.php">Daowat</a></li>
				<li><a href="newsfeed.php">Newsfeed</a></li>
				<li><a href="profile.php?u=<?php echo "$user"; ?>">Profile</a></li>
				<li><a href="messages.php">Message</a></li>
				<li> <a href="followRequest.php">Friends</a></li>
				<li> <a href="notification.php">Notification</a></li>
				<li> <a href="search.php">Search</a></li>
			</ul>
		</nav>
	</div>
	<div style="width: 100%;">
		<?php
			//for getting post
			$getposts = mysql_query("SELECT * FROM posts WHERE id ='$id'") or die(mysql_error());
			$getpostsu_name = mysql_fetch_assoc($getposts);
			$post_added_by = $getpostsu_name['added_by'];
			$user_posted_to = $getpostsu_name['user_posted_to'];
			$post_user_posted_to = $getpostsu_name['user_posted_to'];
			$db_filename = $getpostsu_name['photos'];
			$getposts_num = mysql_num_rows($getposts );
			if ($getposts_num == 0) {
				header('location: newsfeed.php');
			}else {
				echo '
					<div style="padding: 6px; border-bottom: 1px solid #b1b1b1; color: #b4b6ba;">
						<h2 style="font-size: 20px;">More Options</h2>
					</div>
					<form action="" method="POST">
						<div style="background: #fff; border-color: #b4b6ba;">
							<fieldset>';
								if($post_added_by == $user) {
									echo'<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="action_key" value="HIDE_FROM_TIMELINE" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Hide from Timeline</span>
															</div>
															<div>
																<span class="c_g">This post may still appear in other places.</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>';
								}else {
									echo'<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="action_key" value="HIDE_FROM_TIMELINE" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Hide</span>
															</div>
															<div>
																<span class="c_g">Hide this post from newsfeed.</span>
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
														<input name="action_key" value="REPORT_POST" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Report Post</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>';
								}

								echo'
								<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="action_key" value="SAVE_POST" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Save</span>
															</div>
															<div>
																<span class="c_g">Add this to your save items.</span>
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
														<input name="action_key" value="SHARE_POST" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Share</span>
															</div>
															<div>
																<span class="c_g">Share this post on your timeline.</span>
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
														<input name="action_key" value="DAOWAT_POST" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Add to Daowat</span>
															</div>
															<div>
																<span class="c_g">This post show in Daowat timeline.</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>';
								if($post_added_by == $user) {
								echo'<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="action_key" value="ADD_PHOTO" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Add Photo</span>
															</div>
															<div>
																<span class="c_g">You can add photo under this post.</span>
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
														<input name="action_key" value="EDIT_POST" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Edit Post</span>
															</div>
															<div>
																<span class="c_g">You can edit this post.</span>
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
														<input name="action_key" value="EDIT_PRIVACY" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Edit Privacy</span>
															</div>
															<div>
																<span class="c_g">Who can see this post.</span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>';
								if(($user_posted_to == $user) || ($post_added_by == $user)) {
									echo'<label class="lb">
										<div class="v">
											<table class="c">
												<tbody>
													<tr>
														<td class="radio">
															<input name="action_key" value="DELETE_POST" type="radio"></input>
														</td>
														<td>
															<div class="v">
																<div>
																	<span class="c_b">Delete Post</span>
																</div>
																<div>
																	<span class="c_g">Delete this post permanently.</span>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</label>';
								}
 							}
								echo'<label class="lb">
									<div class="v">
										<table class="c">
											<tbody>
												<tr>
													<td class="radio">
														<input name="action_key" value="TURN_OFF_NOTIFICATIONS" type="radio"></input>
													</td>
													<td>
														<div class="v">
															<div>
																<span class="c_b">Turn off notification this Post.</span>
															</div>
															<div>
																<span class="c_g"></span>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</label>
							</fieldset>
						</div>
						<div class="cc">
							<input name="continue" value="Continue" type="submit" class="confirmSubmit"></input>
						</div>
						<div class="cc">
							<input name="cancel" value="Cancel" type="submit" class="cancelSubmit"></input>
						</div>
						<div class="cc">
							<a href="viewPost.php?pid='.$id.'" class="vp">view post</a>
						</div>
					</form>
				';
			} 
		?>
		
	</div>

</div>

</body>
</html>
		
		
		
		
		
		
		
		
		
		
		