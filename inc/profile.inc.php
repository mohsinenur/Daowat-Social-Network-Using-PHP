<script type="text/javascript">
	function clean (e) {
		var textfield = document.getElementById(e);
		var regex = /fuck/gi;
		textfield.value = textfield.value.replace(regex, "Funny!");
	    }
</script>

<?php 
	//Check whether the user has uploaded a cover pic or not
	$check_pic = mysql_query("SELECT cover_pic FROM users WHERE username='$username'");
	$get_pic_row = mysql_fetch_assoc($check_pic);
	$cover_pic_db = $get_pic_row['cover_pic'];
	//check for cover delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$username' AND (discription='updated his cover photo.' OR discription='updated her cover photo.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysql_fetch_assoc($pro_changed);
		$pro_num = mysql_num_rows($pro_changed);
		if ($pro_num == 0) {
			$cover_pic= "img/default_covpic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $cover_pic_db ) {
			$cover_pic= "img/default_covpic.png";
		}else {
			$cover_pic= "userdata/profile_pics/".$cover_pic_db ;
		}
		}

	//Check whether the user has uploaded a profile pic or not
	$check_pic = mysql_query("SELECT profile_pic FROM users WHERE username='$username'");
	$get_pic_row = mysql_fetch_assoc($check_pic);
	$profile_pic_db = $get_pic_row['profile_pic'];
						//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$username' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
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

	//edit profile
	if (isset($_POST['updateProfile'])) {
		header("location: profile_update.php");
	}

	//sent messege
	if (isset($_POST['sendmsg'])) {
		header("location: messages.php?u=$username");
	}



	//follow request system
	if (@($_POST['follow'])) {
		$if_followed = mysql_query("SELECT * FROM follow WHERE user_to='$username' AND user_from='$user'");
		$followed_found = mysql_num_rows($if_followed);
		if ( $followed_found >= 1 ) {
			header("location: profile.php?u=$username");
		}else{
		$user_from = $user;
		$user_to = $username;
		$create_follow = mysql_query("INSERT INTO follow VALUES ('', '$user_from', '$user_to', NOW(), 'no')");
		}
	}

	//unfollow request system
	if (@($_POST['unfollow'])) {
		$if_following = mysql_query("SELECT * FROM follow WHERE user_to='$username' AND user_from='$user'");
		$following_found = mysql_num_rows($if_following);
		if ( $following_found >= 1 ) {
			$delete_follow = mysql_query("DELETE FROM follow WHERE user_from='$user' && user_to='$username' ");
		}else{
			header("location: profile.php?u=$username");
		}
	}
	//unfriend system
	if (@($_POST['unfriend'])) {
		$if_following = mysql_query("SELECT * FROM follow WHERE user_to='$username' AND user_from='$user'");
		$following_found = mysql_num_rows($if_following);
		if ( $following_found >= 1 ) {
			$delete_follow1 = mysql_query("DELETE FROM follow WHERE user_from='$user' && user_to='$username' ");
			$delete_follow2 = mysql_query("DELETE FROM follow WHERE user_from='$username' && user_to='$user' ");
		}else{
			header("location: profile.php?u=$username");
		}
	}

	//name query

	$about_query = mysql_query("SELECT first_name,verify_id FROM users WHERE username='$username'");
	$get_result = mysql_fetch_assoc($about_query);
	$first_name_user = $get_result['first_name'];
	$verify_id_user = $get_result['verify_id'];
?>
<div>
	<div class="prifile_cov" style= "background: url(<?php echo $cover_pic; ?>) repeat center center;">
		<div style="width: 100%; height: 280px;">
			<div style="width: 960px; padding-top: 55px; margin: 0 auto;">
				<ul>
					<li>
						<div class="u_profile" style= "background: url(<?php echo $profile_pic; ?>) repeat;"></div>
					</li>
					<li style="float: left; margin: 84px 0 0 24px; text-shadow: 0px 0px 7px #000; text-align: center;">
						<ul style="line-height: 1.3;">
							<?php 
								if ($verify_id_user == 'yes') {
									echo '<span style="font-size: 25px; margin-right: 8px; float: left; font-weight: 800; color: #ffffff">'.$first_name_user.'</span><div class="verifiedicon" style="background: url(img/verifiLogo.png) repeat; background-size: cover !important;" title="Verified profile"></div>';
								}else {
									echo '<span style="font-size: 25px; margin-right: 13px; float: left; font-weight: 800; color: #ffffff">'.$first_name_user.'</span>';
								}
							 ?>
							
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="profileMainmenu">
		<div>
		<ul style="width: 900px; margin: 0 auto;">
			<li style="float: left; padding: 8px 0;">
				<form action="" method="POST">
					<?php
					if ($user == $username) {
						echo "<button value='button' name='updateProfile' class='frndPokMsg'>Edit your profile</button>";
					}else {
						$check_if_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user' AND user_to='$username') ORDER BY id DESC LIMIT 2");
						$num_follow_found = mysql_num_rows($check_if_follow);
						if ( $num_follow_found != "" ) {
							$check_if_friend = mysql_query("SELECT * FROM follow WHERE (user_from='$username' AND user_to='$user') ORDER BY id DESC LIMIT 2");
							$num_friend_found = mysql_num_rows($check_if_friend);
							if ( $num_friend_found != "" ) {
								echo '<input type="submit" name="unfriend"  class="frndPokMsg" title="Unfriend '.$first_name_user.'" value="Friend" />';
								echo '<input type="submit" name="sendmsg"  class="frndPokMsg" value="Message" />';
							}else {
								echo '<input type="submit" name="unfollow"  class="frndPokMsg" title="Unfollow '.$first_name_user.'" value="Following" />';
								echo '<input type="submit" name="sendmsg"  class="frndPokMsg" value="Message" />';
							}
							
						}else {
							$if_uname_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$username' AND user_to='$user') ORDER BY id DESC LIMIT 2");
							$uname_follow_found = mysql_num_rows($if_uname_follow);
							if ( $uname_follow_found != "" ) {
								echo '<input type="submit" name="follow"  class="frndPokMsg followsYou" title="Follow back '.$first_name_user.'" value="Follows You" />';
								echo '<input type="submit" name="sendmsg"  class="frndPokMsg" value="Message" />';
							}else {
								echo '<input type="submit" name="follow"  class="frndPokMsg" title="follow '.$first_name_user.'" value="Follow" />';
								echo '<input type="submit" name="sendmsg"  class="frndPokMsg" value="Message" />';
							}
						}
					
					}
					?>
			</form>

			</li>
