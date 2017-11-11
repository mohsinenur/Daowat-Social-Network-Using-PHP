<?php 

include ( "./inc/connect.inc.php");
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
}

include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

//chat count 
$buddyList = '0';
$getonlineuser = mysql_query("SELECT first_name,username FROM users WHERE (chatOnlineTime>=now()-300)") or die(mysql_error());

if (mysql_num_rows($getonlineuser) >= '2' ) {
	while ($row = mysql_fetch_assoc($getonlineuser)) {
		$usrnm= $row['username'];
		$check_if_friend = mysql_query("SELECT * FROM follow WHERE (user_from='$usrnm' AND user_to='$user') || (user_from='$user' AND user_to='$usrnm') ORDER BY id DESC LIMIT 2");
		if(mysql_num_rows($check_if_friend) >= '2') {
		$buddyList++;
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style>
	*{
	padding:0;margin:0;box-sizing:border-box;
	}
	table{
	width:100%;
	table-layout:fixed;
	border-collapse:collapse;
	word-wrap:break-word;
	}
	td,th{
	text-align:left;
	border-bottom:1px solid #ddd;
	font-family:Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif;
	font-size:20px;
	}
	td{
	padding:7px;
	}
	tr:nth-child(even){
	background-color:#ffffff;
	}
	tr:nth-child(odd){
	background-color:#f2f2f2;
	}
	#online,#offline{
	float:left;
	width:12px;
	height:12px;
	}
	#offline{
	margin-left:6px;
	width:10px;height:10px;
	}
	#time{
	font-size:14px;
	display:inline-block;
	margin-top:2px;
	}
	</style>
</head>
<body>
<div class="pro_body">
	<div class="pro_header">
		<nav class="pro_hdr_menu">
			<?php include ( "./inc/hdrmenu.inc.php"); ?>
		</nav>
	</div>
	<div style="width: 100%;">
		<div style="margin: 10px; line-height: 18px;">
		<h2 style='font-size: 20px; margin-bottom: 18px; '><a href="messages.php" style="text-decoration: none; color: #828282;">Message</a>  |  <a href="buddylist.php" style="text-decoration: none; color: #0b810b;" >Chat(<?php echo $buddyList; ?>)</a></h2>
			<p style='margin-top: 6px; border-bottom:2px solid #ddd;'></p>
			<div>
			<?php
			echo "<table>";
			
			
			$getonlineuser = mysql_query("SELECT first_name,username FROM users WHERE (chatOnlineTime>=now()-300)") or die(mysql_error());
					if (mysql_num_rows($getonlineuser) >= '1' ) {
					while ($row = mysql_fetch_assoc($getonlineuser)) {
						$usrnm= $row['username'];
						$usrfnm= $row['first_name'];
						
						$check_if_friend = mysql_query("SELECT * FROM follow WHERE (user_from='$usrnm' AND user_to='$user') || (user_from='$user' AND user_to='$usrnm') ORDER BY id DESC LIMIT 2");
						if(mysql_num_rows($check_if_friend) >= '2') {
							echo "<tr><td><a href='message.php?u=".$usrnm."' style='text-decoration: none;color: #0B810B;'>".$usrfnm."</a></td><td><img id=online src='http://www.daowat.com/img/online.png'></td></tr>";
						}
					}
					}
					if($buddyList == '0') {
					echo "<center style='line-height: 1.5;font-size: 15px;font-weight: bold;'><br><br>None of your friends are online</center>";
				}
			echo "</table>";
			?>
			</div>
		</div>
	</div>

</div>

</body>
</html>