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

	//reporting post
	$result = mysql_query("UPDATE posts SET report='1' WHERE id='$id'");
	header("location: newsfeed.php");
}else {
	header('location: index.php');
}

?>