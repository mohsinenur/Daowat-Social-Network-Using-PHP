<?php
//check for noti
$check_for_post_noti = mysql_query("SELECT * FROM post_comments WHERE posted_to='$user' AND posted_by!='$user' AND opened='no' ORDER BY id DESC");
$post_noti_num = mysql_num_rows($check_for_post_noti);


//get msg row
$get_unread_query = mysql_query("SELECT opened FROM pvt_messages WHERE user_to='$user' && opened='no'");
$get_unread = mysql_fetch_assoc($get_unread_query);
$unread_numrows = mysql_num_rows($get_unread_query);
$unread_msg_numrows = $unread_numrows;

//get follow row
$get_follow_query = mysql_query("SELECT opened FROM follow WHERE user_to='$user' && opened='no'");
$get_follow = mysql_fetch_assoc($get_follow_query );
$follow_numrows = mysql_num_rows($get_follow_query );
$unread_follow_numrows = $follow_numrows;

?>