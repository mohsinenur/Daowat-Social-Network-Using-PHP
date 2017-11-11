<?php
session_start();

$conn = new PDO("mysql:host=localhost;dbname=daowatco_td","daowatco_td","sinEmi4334222");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_login'])) {
	header('location: http://www.daowat.com');
}
else {
	$user = $_SESSION['user_login'];
	echo'Nothing found';
}

$sql="UPDATE users SET chatOnlineTime=now() WHERE username='$user'";
$conn->exec($sql);
$conn=null;

?>