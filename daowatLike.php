<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
	
	//inserting daowat  like
	if (isset($_REQUEST['did'])) {
		$dwt_id = $_REQUEST['did'];
	
		$insertDwtlike = mysql_query("INSERT INTO dwt_likes VALUES ('','$user','$dwt_id')");
		header("location: index.php");
	}else {
		header('location: index.php');
	}
}

?>