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
	<title>View all user</title>
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
				<a href="index.php"><h1>View All User</h1></a>
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
	<table class="rightsidemenu">
		<tr style="font-weight: bold;" colspan="10" bgcolor="#4DB849">
			<th>Id</th>
			<th>Full Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Gender</th>
			<th>Sign Up Date</th>
			<th>Verified</th>
			<th>Activated</th>
			<th>blocked</th>
			<th>Edit User</th>
		</tr>
		<tr>
			<?php
			include ( "./inc/connect.inc.php");
			$query = "SELECT * FROM users ORDER BY id DESC";
			$run = mysql_query($query);
			while ($row=mysql_fetch_assoc($run)) {
				$id = $row['id'];
				$fullname = $row['first_name'];
				$username = $row['username'];
				$email = $row['email'];
				$gender = $row['gender'];
				$signupdate = $row['sign_up_date'];
				$verified = $row['verify_id'];
				$activated = $row['activated'];
				$blocked= $row['blocked_user'];
			
			 ?>
			<th><?php echo $id; ?></th>
			<th><?php echo $fullname; ?></th>
			<th><?php echo $username; ?></th>
			<th><?php echo $email; ?></th>
			<th><?php echo $gender; ?></th>
			<th><?php echo $signupdate; ?></th>
			<th><?php echo $verified; ?></th>
			<th><?php echo $activated; ?></th>
			<th><?php echo $blocked; ?></th>
			<th class="edituser"><a href="editUsers.php?user=<?php echo $id; ?>">Edit</a></th>
		</tr>
		<?php } ?>
	</table>
</div>
</body>
</html>
<?php } ?>