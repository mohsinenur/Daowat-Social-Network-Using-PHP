<?php
	$id = $row['id'];
	$body = $row['body'];
	$date_added = $row['date_added'];
	$added_by = $row['added_by'];
	$user_posted_to = $row['user_posted_to'];
	$sharepostid = $row['share_post'];
	$discription = $row['discription'];
	$photos_db = $row['photos'];
	$photos = "http://www.daowat.com/userdata/profile_pics/".$photos_db;
	$get_user_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
	$get_info = mysql_fetch_assoc($get_user_info);
	$profile_pic_db= $get_info['profile_pic'];
	$gender_user_db = $get_info['gender'];
	$add_by = $get_info['first_name'];
	$get_fname_info = mysql_query("SELECT * FROM users WHERE username='$user_posted_to'");
	$get_fname_info = mysql_fetch_assoc($get_fname_info);
	$post_to_fname = $get_fname_info['first_name'];
	//share post info
	$get_sharepost = mysql_query("SELECT * FROM posts WHERE id='$sharepostid'");
	$post_info = mysql_fetch_assoc($get_sharepost );
	$sp_id = $post_info['id'];
	$sp_added_by = $post_info['added_by'];
	$sp_added_photo = $post_info['photos'];
	$sp_get_user_info = mysql_query("SELECT * FROM users WHERE username='$sp_added_by'");
	$sp_get_info = mysql_fetch_assoc($sp_get_user_info);
	$sp_profile_pic_db= $sp_get_info['profile_pic'];
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
	if (strlen($body) > 4000) {
			$body = substr($body, 0, 2000)." <a href='viewPost.php?pid=".$id."' style='text-decoration: none; color: #0B810B;'>... read more</a>";
		}else {
			$body = $body;
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
				$discription = "shared his <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>".$post_item."</a>";
			}else if ($gender_user_db == '2') {
				$discription = "shared her <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>".$post_item."</a>";
			}
		}else {
			$discription = "shared ".$sp_add_by."'s <a href='viewPost.php?pid=".$sharepostid."' style='text-decoration: none; color: #0B810B;'>".$post_item."</a>";
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
			echo "<div class='postBody'>";
			
			if ($user_posted_to == $added_by) {
					echo "<div style='margin: 0px 10px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $add_by's Profile\">$add_by</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div>";
			}else {
					echo "<div style='margin: 0px 10px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $add_by's Profile\">$add_by &nbsp;&nbsp;</a><h style='font-size: 13px; color: #656262;'>>&nbsp;&nbsp;</h><a href='profile.php?u=$user_posted_to' title=\"Go to $post_to_fname's Profile\">$post_to_fname</a> <span style='color: #9E9E9E; font-weight: normal;'>$discription</span></div>";
			}
			echo "<div class='posted_body'><div class='posted_date'>$date_added</div><p style='word-break: break-all;'>".nl2br($body)."</p>";
				if ($photos_db == "") {
					echo'</div>';
				}else {
					echo "<a href='viewPost.php?pid=".$id."'><img src='$photos' style='max-width: 420px; width: 100%; margin-top: 5px; border: 1px solid #ddd;'/></a></div></div>";
				}
				
				if($sharepostid != 0) {
					
					$sp_body = $post_info['body'];
					$sp_dwtbody = $post_info['daowat_body'];
					$sp_dwtgive = $post_info['daowat_give'];
					$sp_discription = $post_info['discription'];
					$sp_date_added = $post_info['date_added'];
					$sp_photos_db = $post_info['photos'];
					$sp_photos = "http://www.daowat.com/userdata/profile_pics/".$sp_photos_db;
					if($sp_dwtgive != 0) {
						$sp_body = $sp_dwtbody;
					}
					if (strlen($sp_body) > 1500) {
							$sp_body = substr($sp_body, 0, 1500)." <a href='viewPost.php?pid=".$sp_id."' style='text-decoration: none; color: #0B810B;'>... read more</a>";
						}else {
							$sp_body = $sp_body;
						}
						
						echo'
						<div style="border: 1px solid; border-color: #e9eaed #e9eaed #d1d1d1; padding: 6px 6px 0px; margin: 0px 0px 10px;">';
						
							echo "<div class='posted_by' style='font-size: 12px;'><a href='profile.php?u=$sp_added_by' title=\"Go to $sp_add_by's Profile\">$sp_add_by</a> <span style='color: #9E9E9E; font-weight: normal;'>$sp_discription</span></div>";
							echo "<div class='posted_body' style='font-size: 12px; color: #666;'><div class='posted_date'>$sp_date_added</div><p>".nl2br($sp_body)."</p>";
								if ($sp_photos_db == "") {
									//nothing
								}else {
									echo "<a href='viewPost.php?pid=".$sp_id."'><img src='$sp_photos' style='max-width: 420px; width: 100%; margin-top: 5px; border: 1px solid #ddd;'/></a>";
								}
						
						echo'</div></div></div>';
						
					}
					
				if(($sharepostid == 0) && ($photos_db == "")) {
					echo'</div>';
				}
				
				echo "
				<div class='dwtlikeComShare'>";
				$like_query = mysql_query("SELECT * FROM post_likes WHERE user_name='$user' AND post_id='$id' ORDER BY id DESC LIMIT 1");
				$rows_uname = mysql_num_rows($like_query);
				if ($rows_uname == 1) {
					echo "<a href='like.php?upid=".$id."' style='color: #0B810B; margin-left: 10px;' >Liked . </a>";
					echo "<a href='plikesppl.php?plikep=".$id."' style='color: #0B810B;' >$count_like people</a>";
				}else {
					echo "<a href='like.php?pid=".$id."' style=' margin-left: 10px;' >Like . </a>";
					if($count_like == 0) {
					
					}else {
						echo "<a href='plikesppl.php?plikep=".$id."' style='color: #0B810B;' >$count_like people</a>";
					}
					
				}
				echo "<a href='viewPost.php?pid=".$id."' style=' margin-left: 10px;'>Comments ($count)</a>
				<a href='moreoptions.php?pid=".$id."' style=' margin-left: 10px;'>More</a>
				</div>
			</div>";
	
	?>