<ul>
	<li><a href="index.php">Daowat</a></li>
	<li><a href="newsfeed.php">Newsfeed</a></li>
	<li><a href="profile.php?u=<?php echo "$user"; ?>">Profile</a></li>
	<?php 
		if($unread_numrows >= 1) 
		{
			echo "<li><a href='messages.php' style='color: yellow;'>Message(".$unread_msg_numrows.")</a></li>";
		}else {
			echo "<li><a href='messages.php'>Message</a></li>";
		}
		if($follow_numrows >= 1) 
		{
			echo "<li><a href='followRequest.php' style='color: yellow;'>Friends(".$unread_follow_numrows.")</a></li>";
		}else {
			echo "<li><a href='followRequest.php'>Friends</a></li>";
		}
		if($post_noti_num >= 1) 
		{
			echo "<li><a href='notification.php' style='color: yellow;'>Notification(".$post_noti_num.")</a></li>";
		}else {
			echo "<li><a href='notification.php'>Notification</a></li>";
		}
	 ?>
	<li><a href="search.php">Search</a></li>
</ul>