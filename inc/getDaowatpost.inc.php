<?php

	$id = $row['id'];
	$body = $row['body'];
	$date_added = $row['date_added'];
	$added_time = $row['post_time'];
	$added_by = $row['added_by'];
	$photos_db = $row['photos'];
	$photos = "./userdata/daowat_pics/".$photos_db;
	$get_posted_to_info = mysql_query("SELECT * FROM users WHERE username='$added_by'");
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



	//Get Relevant Comments
	$get_comments = mysql_query("SELECT * FROM daowat_comments WHERE daowat_id='$id' ORDER BY id DESC");
	$count = mysql_num_rows($get_comments);
	$daowat = mysql_fetch_assoc($get_comments);
	$daowat_body = $daowat['daowat_body'];
	$daowat_to = $daowat['daowat_to'];
	$daowat_by = $daowat['daowat_by'];

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
	//getting all comments
	$get_comments = mysql_query("SELECT * FROM daowat_comments WHERE daowat_id='$id' ORDER BY id DESC");
	$count = mysql_num_rows($get_comments);

	//getting all like
	$get_like = mysql_query("SELECT * FROM dwt_likes WHERE dwt_id='$id' ORDER BY id DESC");
	$count_like = mysql_num_rows($get_like);

	//showing data on timeline
	echo "<div class='postBody daowat_search_result_box'>";
				echo "<div style='float: left; margin-left: 10px;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$added_by\" height='45' width='45'  /></div>";
		
			echo "<div style=' margin-top: 7px;'><div class='posted_by'><a href='profile.php?u=$added_by' title=\"Go to $added_by's Profile\">$add_by_fname</a></div><br /><br />";
			echo "<div class='posted_date'>".formatDate($added_time)."</div></div> <br /> 
			<div class='posted_body'>".nl2br($body)."<br> ";
			if ($photos_db == "") {
				//nothing
			}else {
				echo "<img src='$photos' style='max-height: 450px; max-width: 486px; border: 1px solid #ddd;'/>";
			}
			echo "
			</div>
			<br /><hr />
			<div class='dwtlikeComShare'>";
			$like_query = mysql_query("SELECT * FROM dwt_likes WHERE user_name='$user' AND dwt_id='$id' ORDER BY id DESC LIMIT 1");
			$rows_uname = mysql_num_rows($like_query);
			if ($rows_uname == 1) {
				echo "<a href='' style='color: #0B810B; cursor: text;' >Liked ($count_like)</a>";
				echo "<a href='javascript:;' onClick='javascript:toggle$id()'>Comments ($count)</a>";
			}else if ($user != '') {
				echo "<a href='daowatLike.php?did=".$id."' >Like . $count_like</a>";
				echo "<a href='javascript:;' onClick='javascript:toggle$id()'>Comments ($count)</a>";
			}else {
				echo "<a href='signin.php' >Like . $count_like</a>";
				echo "<a href='signin.php' >Comments ($count)</a>";
			}
			
			if ($added_by == $user) {
						echo "<a onclick='return confirm_delete();' href='deleteDaowat.php?did=".$id."' >Delete</a>";
					}
			echo "</div>
		</div>
		<div id='toggleComment$id' class='commentBody'>
		<br />
		<iframe src='./daowat_cmntsFrame.php?id=$id' frameborder='0'></iframe>
		</div> <br />";
?>