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

if (isset($_REQUEST['pid'])) {
	$id = $_REQUEST['pid'];

	//cancel report
	$result = "UPDATE posts SET report='0' WHERE id='$id'";
	if (mysql_query($result)) {
		echo "<script>alert('Successfully Cancel Report.')</script>";
		echo "<script>window.open('report.php','_self')</script>";
	}
}

?>
<?php } ?>