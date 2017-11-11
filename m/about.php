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

include ( "./inc/headerfmnoti.inc.php");

//update online time
$sql = mysql_query("UPDATE users SET chatOnlineTime=now() WHERE username='$user'");
?>
<?php 
	$username ="";
	$firstname ="";
	if (isset($_GET['u'])) {
		$username = mysql_real_escape_string($_GET['u']);
		if (ctype_alnum($username)) {
			//check user exists
			$check = mysql_query("SELECT username, first_name FROM users WHERE username='$username'");
			if (mysql_num_rows($check)===1) {
				$get = mysql_fetch_assoc($check);
				$username = $get['username'];
				$firstname = $get['first_name'];
			}
			else {
				die();
			}
		}
	}

	$get_title_info = mysql_query("SELECT * FROM users WHERE username='$username'");
	$get_title_fname = mysql_fetch_assoc($get_title_info);
	$title_fname = $get_title_fname['first_name'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> - Daowat</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://www.daowat.com/img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>
<div class="pro_body">
		<div class="pro_header">
			<nav class="pro_hdr_menu">
				<?php include ( "./inc/hdrmenu.inc.php"); ?>
			</nav>
		</div>


	<?php 

	$result = mysql_query("SELECT * FROM users WHERE username='$username'");
	$num = mysql_num_rows($result);
	if ($num == 1) {
	include ( "./inc/profile.inc.php");
	echo '
		<div class="pro_header"> 
			<nav class="pro_hdr_menu">
				<ul>
					<li><a href="profile.php?u='.$username.'">Post</a></li>
					<li style="border-bottom: 4px solid #2AED25; color: #2AED25;"><a href="about.php?u='.$username.'">About</a></li>
					<li><a href="friends.php?u='.$username.'">Friend</a></li>
					<li><a href="photo.php?u='.$username.'">Photo</a></li>
					<li><a href="note.php?u='.$username.'">Note</a></li>
				</ul>
			</nav>
	</div>';
	$get_msg_num = mysql_query("SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
	$msg_count = mysql_num_rows($get_msg_num);
	if (($msg_count >=1 ) || ($username == $user)) {

	$about_query = mysql_query("SELECT school,concentration,city,hometown,queote,bio,company,position,mobile,pub_email,relationship FROM users WHERE username='$username'");
					$get_result = mysql_fetch_assoc($about_query);
					$school_name_user = $get_result['school'];
					$concentration_name_user = $get_result['concentration'];
					$city_name_user = $get_result['city'];
					$hometown_name_user = $get_result['hometown'];
					$user_queote = $get_result['queote'];
					$user_bio = $get_result['bio'];
					$user_company = $get_result['company'];
					$user_position = $get_result['position'];
					$user_mobile = $get_result['mobile'];
					$user_pub_email = $get_result['pub_email'];
					$user_relationship = $get_result['relationship'];
	
		echo '<div class="uiaccountstyle">
				<p class="uiaccountstyleHdr">WORK AND EDUCATION';
					if ($user==$username) {
						echo '<a href="editprofile.php?edit=work_education" style="float: right; text-decoration: none; font-size: 12px; color: #0B810B">Edit</a>';	
					}else {
						
					}
				echo '</p>
				<div style="padding: 10px;">';
				if ($user_company == '' && $user_position == '' && $school_name_user == '' && $concentration_name_user == '') {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #7B7B7B;">No work or education history<br></p>
					';
				}
				
				if ($user_company == '' && $user_position == '') {
					//nothing
				}else if ($user_company != '' && $user_position == '')  {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: rgb(33, 35, 33);">'.$user_company.'<br></p>
					';
				}else if ($user_company == '' && $user_position != '')  {
					echo '
						<p style=" font-weight: bold; color: #7B7B7B;">'.$user_position.'<br></p></br>
					';
				}else if ($user_company != '' && $user_position != '') {
					echo '
				<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$user_company.'<br></p>
				<p style=" font-weight: bold; color: #7B7B7B;">'.$user_position.'<br></p>
					';
				}

				 if ($school_name_user == '' && $concentration_name_user == '')  {
				 	//nothing
				}else if ($school_name_user != '' && $concentration_name_user == '')  {
					echo '
				<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$school_name_user.'<br></p>
					';
				}else if ($school_name_user == '' && $concentration_name_user != '')  {
					echo '
				<p style=" font-weight: bold; color: #7B7B7B;">'.$concentration_name_user.'<br></p>
					';
				}else if ($school_name_user != '' && $concentration_name_user != '')  {
					echo '
				<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$school_name_user.'<br></p>
				<p style=" font-weight: bold; color: #7B7B7B;">'.$concentration_name_user.'</p>
					';
				}
				
			echo '
			</div><p class="uiaccountstyleHdr">MOBILE AND EMAIL';
					if ($user==$username) {
						echo '<a href="editprofile.php?edit=editcontact" style="float: right; text-decoration: none; font-size: 12px; color: #0B810B">Edit</a>';	
					}else {
						
					}
			echo '</p>
				<div style="padding: 10px;">';
				if ($user_mobile == '' && $user_pub_email == '') {
					echo '</br>';
				}else if ($user_mobile == '' && $user_pub_email != '')  {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$user_pub_email.'</p>
						<p style=" font-weight: bold;  color: #7B7B7B;">Public Email</p>
					';
				}else if ($user_mobile != '' && $user_pub_email == '')  {
					echo '
						<p style="font-size: 15px;  font-weight: bold; color: #0B810B;">'.$user_mobile.'</p>
						<p style=" font-weight: bold;  color: #7B7B7B;">Mobile</P>
					';
				}else if ($user_mobile != '' && $user_pub_email != '') {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$user_mobile.'</p>
						<p style=" font-weight: bold;color: #7B7B7B;">Mobile</P>
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$user_pub_email.'</p>
						<p style=" font-weight: bold; color: #7B7B7B;">Public Email</p>
					';
				}
				echo '</div>
			<p class="uiaccountstyleHdr">CURRENT CITY AND HOMETOWN';
					if ($user==$username) {
						echo '<a href="editprofile.php?edit=editlocation" style="float: right; text-decoration: none; font-size: 12px; color: #0B810B">Edit</a>';	
					}else {
						
					}
			echo '</p>
				<div style="padding: 10px;">';
				if ($city_name_user == '' && $hometown_name_user == '') {
					echo '</br>';
				}else if ($city_name_user == '' && $hometown_name_user != '')  {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$hometown_name_user.'</p>
						<p style=" font-weight: bold; color: #7B7B7B;">Hometown</p>
					';
				}else if ($city_name_user != '' && $hometown_name_user == '')  {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$city_name_user.'</p>
						<p style=" font-weight: bold; color: #7B7B7B;">Current City</P>
					';
				}else if ($city_name_user != '' && $hometown_name_user != '') {
					echo '
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$city_name_user.'</p>
						<p style=" font-weight: bold; color: #7B7B7B;">Current City</P>
						<p style="font-size: 15px; font-weight: bold; color: #0B810B;">'.$hometown_name_user.'</p>
						<p style=" font-weight: bold; color: #7B7B7B;">Hometown</p>
					';
				}
				echo '</div>';
				echo '<p class="uiaccountstyleHdr">DETAILS ABOUT';
						if ($user==$username) {
							echo '<a href="editprofile.php?edit=editdetailsabout" style="float: right; text-decoration: none; font-size: 12px; color: #0B810B">Edit</a>';	
						}else {
							
						}
				echo '</p>
					<div style="padding: 10px;">
					<p style=" color: #0B810B; font-size: 14px; line-height: 18px; "> '.nl2br($user_bio).'<br></p>
				</div>';

			echo '<p class="uiaccountstyleHdr">FAVORITE QUEOTE';
					if ($user==$username) {
						echo '<a href="editprofile.php?edit=editqueote" style="float: right; text-decoration: none; font-size: 12px; color: #0B810B">Edit</a>';	
					}else {
						
					}
			echo '</p>
				<div style="padding: 10px;">
				<p style=" color: #0B810B; font-size: 14px; line-height: 18px; ">'.nl2br($user_queote).'<br></p>
			</div></div></div>';
		}else {
			echo "<p style='text-align: center; color: #7B7B7B; margin: 30px; font-weight: bold; font-size: 20px;'>Sorry! Nothing to view. </p>";
		}
	}else {
	header("location: profile.php?u=$user");
} 

?>
</div>
<div>
		<?php include("./inc/footer.inc.php") ?>
	</div>
</body>
</html>