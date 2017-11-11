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
.daowat_by {
	color: #0B810B; 
	text-decoration: none;
}
.daowat_by:hover {
	text-decoration: underline;
}
/* commentBody styel from here*/

.commentPostText {
	margin-left: 52px; 
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

$getid = $_GET['id'];

$daowat_body = htmlspecialchars(@$_POST['daowat_body'], ENT_QUOTES);
$daowat_body = trim($daowat_body);
if ($daowat_body != "") {
if (isset($_POST['daowatComment' . $getid . ''])) {
	$daowat_body = $_POST['daowat_body'];
	$date_added = date("Y-m-d");
	$query = mysql_query("SELECT id,added_by  FROM daowat WHERE id='$getid'");
	$query_row = mysql_fetch_assoc($query);
	$daowat_to = $query_row['added_by'];
	$insertPost = mysql_query("INSERT INTO daowat_comments VALUES ('','$daowat_body','$date_added',NOW(),'$user','$daowat_to','no','$getid')");
}
}
//post query
	$query = mysql_query("SELECT id,added_by  FROM daowat WHERE id='$getid'");
	$query_row = mysql_fetch_assoc($query);
	$daowat_to = $query_row['added_by'];
	//getting post by gender
	$dwtby_query = mysql_query("SELECT * FROM users WHERE username='$daowat_to'");
	$dwtby_gender_row = mysql_fetch_assoc($dwtby_query);
	$dwtby_gender_value = $dwtby_gender_row['gender'];
	//getting user gender
	$usergender_query = mysql_query("SELECT * FROM users WHERE username='$user'");
	$user_gender_row = mysql_fetch_assoc($usergender_query);
	$user_gender_value = $user_gender_row['gender'];
?>

<?php 
	if ($dwtby_gender_value == 2) {
		if (($daowat_to == $user) || ($user_gender_value == 2)) {
			echo "
			<div style='margin: 0 52px;'>
			<form action='daowat_cmntsFrame.php?id=".$getid."' method='POST' name='daowatComment".$getid."'>
				<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='daowat_body' placeholder= 'Leave your comment here!'>
				<input type='submit' name='daowatComment".$getid."' class='commentSubmit' value='Comment'>
			</form>
			</div>
		";
	}else {
		$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$daowat_to' AND user_to='$user' LIMIT 2");
			$female_msg = mysql_num_rows($get_msg_num);
			if ($female_msg >=1 ) {
				echo "
					<div style='margin: 0 52px;'>
					<form action='daowat_cmntsFrame.php?id=".$getid."' method='POST' name='daowatComment".$getid."'>
						<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='daowat_body' placeholder= 'Leave your comment here!'>
						<input type='submit' name='daowatComment".$getid."' class='commentSubmit' value='Comment'>
					</form>
					</div>
				";
			}else {
				echo "<p style=' text-align: center; font-size: 18px; color: #7B7B7B; font-weight: bold;'>Sorry! You can not leave comment here.</p>";
			}
		}
	}else {
		echo "
			<div style='margin: 0 52px;'>
			<form action='daowat_cmntsFrame.php?id=".$getid."' method='POST' name='daowatComment".$getid."'>
				<input style='padding: 10px 3px; width: 83%; margin: 0 0 5px 0; resize: none; border: 1px solid #0B810B;' name='daowat_body' placeholder= 'Leave your comment here!'>
				<input type='submit' name='daowatComment".$getid."' class='commentSubmit' value='Comment'>
			</form>
			</div>
		";
	}
	?>


<?php
//Get relevant daowat
$get_comments = mysql_query("SELECT * FROM daowat_comments WHERE daowat_id='$getid' ORDER BY id DESC");
$count = mysql_num_rows($get_comments);
if ($count != 0) {
while ($comment = mysql_fetch_assoc($get_comments)) {
	$daowat_body = $comment['daowat_body'];
	$date_added = $comment['date_added'];
	$daowat_to = $comment['daowat_to'];
	$dawat_by = $comment['daowat_by'];
	$get_user_info = mysql_query("SELECT * FROM users WHERE username='$dawat_by'");
	$get_info = mysql_fetch_assoc($get_user_info);
	$profile_pic_db= $get_info['profile_pic'];
	$daowat_by = $get_info['first_name'];
	$dwt_user_info = mysql_query("SELECT * FROM users WHERE username='$daowat_by'");
	$fname_info = mysql_fetch_assoc($dwt_user_info);
	
	//check for propic delete
						$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$dawat_by' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
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
	<div style='float: left; margin: 0 10px 0 0;'><img src='$profile_pic' style= 'border-radius: 22px'; title=\"$daowat_by\" height='38' width='38'  /></div>
	<div style='margin-left: 48px;'>
	<b><a href='profile.php?u=$dawat_by' title=\"Go to $daowat_by's Profile\" target='_top' class='daowat_by'>$daowat_by</a></b><p style='font-size: 10px; margin: 0;'>".$date_added."</p>
	".nl2br($daowat_body)."
	
	</div>
	</div><br>";
	
}
}else {
	echo "<center><br><br><br>Opps! There is no comment to view.</center>";
}

?>