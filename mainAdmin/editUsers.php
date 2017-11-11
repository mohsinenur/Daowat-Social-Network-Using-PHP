<?php 
ob_start();
session_start();
if (!isset($_SESSION['admin_user'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['admin_user'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit User Information</title>
	<link rel="icon" href="../img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="adminStyle.css">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<script type="text/javascript" src="./js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('body').on('keydown', '#search', function(e) {
		    console.log(this.value);
		    if (e.which === 32 &&  e.target.selectionStart === 0) {
		      return false;
		    }  
		  });
		});
	</script>
</head>
<body>
<div class="main">
	<table class="adminHeader">
		<tr>
			<th>
				<a href="index.php"><h1>Edit User Information</h1></a>
			</th>
			<th class="search">
				<form action="search.php" method="get">
					<input type="text" id="search" name="keywords" placeholder="Search Here..."  />
					<select name="topic" class="search_topic">
						<option>User</option>
						<option>Post</option>
						<option>Daowat</option>
					</select>
					<button type="submit" name="search" ><img src="../img/search.png" style="margin: 0 0 -12px 1px; padding: 0;" height="33" width="33"></button>
				</form>
			</th>
		</tr>
	</table>
	<table class="adminmenu">
		<tr>
			<th style="background-color: #686B68;"><a href="users.php"><h1>User</h1></a></th>
			<th><a href="posts.php"><h1>Post</h1></a></th>
			<th><a href="daowat.php"><h1>Daowat</h1></a></th>
			<th><a href="report.php"><h1>Report</h1></a></th>
			<th><a href="logout.php"><h1 style="color: #292929;">Logout</h1></a></th>
		</tr>
	</table>
	<ul class="userinfo">
		<?php include ( "./inc/connect.inc.php");

			if (isset($_GET['user'])) {
				$uid = $_GET['user'];
			//getting data
			$query = "SELECT * FROM users WHERE id=$uid";
			$run = mysql_query($query);
			while ($row=mysql_fetch_assoc($run)) {
				$id = $row['id'];
				$fullname = $row['first_name'];
				$username = $row['username'];
				$email = $row['email'];
				$password = $row['password'];
				$gender = $row['gender'];
				$signupdate = $row['sign_up_date'];
				$verified = $row['verify_id'];
				$activated = $row['activated'];
				$blocked = $row['blocked_user'];
				$profile_pic_db = $row['profile_pic'];
				if ($profile_pic_db == "") {
					$profile_pic = "../img/default_propic.png";
				}else {
					$profile_pic = "../userdata/profile_pics/".$profile_pic_db;
				}
			}
		}
		//update information
		$updateinfo = @$_POST['updateinfo'];
		if ($updateinfo) {
			$update_id = strip_tags(@$_POST['edit_id']);
			$update_fname = strip_tags(@$_POST['edit_fname']);
			$update_uname = strip_tags(@$_POST['edit_uname']);
			$update_email = strip_tags(@$_POST['edit_email']);
			$update_pass = strip_tags(@$_POST['edit_pass']);
			$update_gender = strip_tags(@$_POST['edit_gender']);
			$update_signupdate = strip_tags(@$_POST['edit_signupdate']);
			$update_activated = strip_tags(@$_POST['edit_activated']);
			$update_verified = strip_tags(@$_POST['edit_verified']);
			$update_blocked = strip_tags(@$_POST['edit_blocked']);

			if (($update_id == "") || ($update_fname == "") || ($update_uname == "") || ($update_email == "") || ($update_pass == "") || ($update_gender == "") || ($update_signupdate == "") || ($update_activated == "") || ($update_verified == "")  || ($update_blocked == "")) {
				echo "<script>alert('Any of the fields is empty!')</script>";
			}else {
				$update_query = "UPDATE users SET id='$update_id', first_name='$update_fname', username='$update_uname', email='$update_email', password='$update_pass', gender='$update_gender',sign_up_date='$update_signupdate', verify_id='$update_verified', activated='$update_activated', blocked_user='$update_blocked'  WHERE id='$uid' ";
				if (mysql_query($update_query)) {
					echo "<script>alert('Successfully Information Updated.')</script>";
					echo "<script>window.open('editUsers.php?user=$uid','_self')</script>";
				}
				
			}
		}
		?>
		<form method="POST" action="editUsers.php?user=<?php echo $id; ?>" enctype="multipart/form-data" style="padding: 22px;">
			<div>
				<table class="edituserdata" style="float: left;">
					<tr >
						<td><img src="<?php echo $profile_pic; ?>" style="height: 150px; width: 150px" /></td>
					</tr>
				</table>
			</div>
			<div style="margin-left: 370px;">
				<table class="edituserdata">
					<tr>
						<td>Id:</td>
						<td> <input type="text" name="edit_id" value="<?php echo $id; ?>" /></td>
					</tr>
					<tr>
						<td>Full Name:</td>
						<td><input type="text" name="edit_fname" value="<?php echo $fullname; ?>" /></td>
					</tr>
					<tr>
						<td>User Name:</td>
						<td><input type="text" name="edit_uname" value="<?php echo $username; ?>" /></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><input type="text" name="edit_email" value="<?php echo $email; ?>" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" name="edit_pass" value="<?php echo $password; ?>" /></td>
					</tr>
					<tr>
						<td>Gender: </td>
						<td><input type="text" name="edit_gender" value="<?php echo $gender; ?>" /></td>
					</tr>
					<tr>
						<td>Sign Up Date: </td>
						<td><input type="text" name="edit_signupdate" value="<?php echo $signupdate; ?>" /></td>
					</tr>
					<tr>
						<td>Activated: </td>
						<td><input type="text" name="edit_activated" value="<?php echo $activated; ?>" /></td>
					</tr>
					<tr>
						<td>Verified: </td>
						<td><input type="text" name="edit_verified" value="<?php echo $verified; ?>" /></td>
					</tr>
					<tr>
						<td>Blocked: </td>
						<td><input type="text" name="edit_blocked" value="<?php echo $blocked; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="updateinfo" style="cursor: pointer; width: 105px; background-color: #CC8D52; color: #FFF; font-weight: bold;" placeholder="Change Data" /></td>
					</tr>
				</table>
			</div>
		</form>
	</ul>
</div>
</body>
</html>
<?php } ?>