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

if (isset($_REQUEST['msgid'])) {
	$id = $_REQUEST['msgid'];
	$id = mysql_real_escape_string($id);

	//getting u name
	$u_name_query = mysql_query("SELECT * FROM pvt_messages WHERE id='$id'");
	$getuname = mysql_fetch_assoc($u_name_query);
	$user_to = $getuname['user_to'];
	$user_from = $getuname['user_from'];
	//deleting msg
	if(($user_to == $user) || ($user_from == $user)) {
	$result = mysql_query("DELETE FROM pvt_messages WHERE id='$id'");
	} else {
	
	}
	
	if ( $user_to == $user) {
		header("location: messages.php?u=$user_from");
	}else {
		header("location: messages.php?u=$user_to");
	}
}else {
	header('location: index.php');
}

?>