<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}

if (isset($_REQUEST['pid'])) {
	$id = $_REQUEST['pid'];
	//delete from directory
	$get_file = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get_file_name = mysql_fetch_assoc($get_file);
	$db_filename = $get_file_name['photos'];
	$delete_file = unlink("./userdata/profile_pics/".$db_filename);
	//delete post
	$result = mysql_query("DELETE FROM posts WHERE id='$id'");
	header("location: note.php?u=$user");
}else {
	header('location: index.php');
}

?>