<?php 
ob_start();
session_start();
if (!isset($_SESSION['admin_user'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['admin_user'];

?>
<?php include ( "./inc/connect.inc.php"); ?>
<?php  
if (isset($_REQUEST['dpid'])) {
	$id = $_REQUEST['dpid'];
	//delete from directory
	$get_file = mysql_query("SELECT * FROM posts WHERE id='$id'");
	$get_file_name = mysql_fetch_assoc($get_file);
	$db_filename = $get_file_name['photos'];
	$delete_file = unlink("../userdata/profile_pics/".$db_filename);

	//delete post
	$result = mysql_query("DELETE FROM posts WHERE id='$id'");
		header("location: report.php");
}
?>

<?php } ?>