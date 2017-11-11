<?php 
 include ( "inc/connect.inc.php");
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}
 //showmore for daowat home post
 $lastid = $_REQUEST['lastid'];
 if (isset($lastid)) {
 	$lastid = $_REQUEST['lastid'];
 }else {
 	header("location: index.php");
 }
  
 if ($lastid >= 1) {
		//timeline query table
		$getposts = mysql_query("SELECT * FROM posts WHERE daowat_give !='0' AND id < $lastid ORDER BY id DESC LIMIT 7") or die(mysql_error());
		if (mysql_num_rows($getposts)) {
			while ($row = mysql_fetch_assoc($getposts)) {
			include ( "./inc/newsfeed.inc.php" );
			$lastvalue = $row['id'];
		}
			echo '<li class="getmore" id="'.$lastvalue.'" >Show More</li>';
		}else {
			echo '<li class="nomorepost">Opps! Nothing more found.</li>';
	}
 }

?>