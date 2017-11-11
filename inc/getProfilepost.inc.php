<?php
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
	$profile_pic_db= $get_info['profile_pic'];
	$add_by_fname = $get_info['first_name'];
	
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
	if (strlen($body) > 5000) {
			$body = substr($body, 0, 5000)." <a href='viewPost.php?pid=".$id."' style='text-decoration: none; color: #0B810B;'>... read more</a>";
		}else
			$body = $body;

	//Get Relevant Comments
	$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
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
	<script type="text/javascript">
	function confirm_delete() {
		return confirm('Are you sure want to delete this?');
	}
	</script>

	<?php
	//count comment
	$get_comments = mysql_query("SELECT * FROM post_comments WHERE post_id='$id' ORDER BY id DESC");
	$count = mysql_num_rows($get_comments);
	//getting all like
	$get_like = mysql_query("SELECT * FROM post_likes WHERE post_id='$id' ORDER BY id DESC");
	$count_like = mysql_num_rows($get_like);
	//showing data on profile
			echo "<div class='postBody post_search_result_box'>";
					echo "<div style='float: left; margin-left: 10px;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>";
		
			if ($user_posted_to == $added_by) {
					echo "<div style=' margin-top: 7px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />";
			}else {
					echo "<div style=' margin-top: 7px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $added_by's Profile\">$add_by_fname &nbsp;&nbsp;</a><h style='font-size: 13px; color: #656262;'>>></h><a href='profile.php?u=$user_posted_to' title=\"Go to $user_posted_to's Profile\">$posted_to_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div><br /><br />";
			}
				echo "<div class='posted_date'>$date_added</div></div> <br /> 
				<div class='posted_body'>".nl2br($body)."<br> ";
				if ($photos_db == "") {
					//nothing
				}else {
					echo "<a href='viewPost.php?pid=".$id."'><img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/></a>";
				}
				echo "
				</div>
				<br /><hr />
				<div class='likeComShare'>";
				$note_check =  mysql_query("SELECT * FROM posts WHERE id='$id'");
				$get_note = mysql_fetch_assoc($note_check);
				$get_note_row = $get_note['note'];
				if ($get_note_row == 0) {
				$like_query = mysql_query("SELECT * FROM post_likes WHERE user_name='$user' AND post_id='$id' ORDER BY id DESC LIMIT 1");
				$rows_uname = mysql_num_rows($like_query);
				if ($rows_uname == 1) {
					echo "<a href='' style='color: #0B810B;' >Liked . $count_like</a>";
				}else {
					echo "<a href='like.php?pid=".$id."' >Like . $count_like</a>";
				}
				}
				echo "<a href='viewPost.php?pid=".$id."'>Comments ($count)</a>";
					if (($added_by == $user) || ($user_posted_to == $user)) {
						echo "<a onclick='return confirm_delete();' href='deletePost.php?pid=".$id."' >Delete</a>";
					}
					
					echo "</div>
			</div>
			<div id='toggleComment$id' class='commentBody'>
			<br />
			<iframe src='./comment_frame.php?id=$id' frameborder='0'></iframe>
			</div> <br />";
		?>