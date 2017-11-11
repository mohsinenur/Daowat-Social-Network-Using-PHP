<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}

if (isset($_REQUEST['did'])) {
	$id = $_REQUEST['did'];
	//delete from directory
	$get_file = mysql_query("SELECT * FROM daowat WHERE id='$id'");
	$get_file_name = mysql_fetch_assoc($get_file);
	$db_filename = $get_file_name['photos'];
	$db_user_name = $get_file_name['added_by'];
	if($db_user_name == $user) {
		$delete_file = unlink("http://www.daowat.com/userdata/daowat_pics/".$db_filename);
		//delete post
		$result1 = mysql_query("DELETE FROM dwt_likes WHERE dwt_id='$id'");
		$result = mysql_query("DELETE FROM daowat WHERE id='$id'");
		header("location: index.php");
	}else {
		header("location: index.php");
	}
	
}else {
	header('location: index.php');
}

?>