<?php include ( "./inc/connect.inc.php" ); ?>
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
	<title>Search • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
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
				<a href="index.php"><h1>Admin Pannel</h1></a>
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
			<th><a href="users.php"><h1>User</h1></a></th>
			<th><a href="posts.php"><h1>Post</h1></a></th>
			<th><a href="daowat.php"><h1>Daowat</h1></a></th>
			<th><a href="report.php"><h1>Report</h1></a></th>
			<th><a href="logout.php"><h1 style="color: #292929;">Logout</h1></a></th>
		</tr>
	</table>
	<div class="">
	<center>
		<?php
			if (($_GET['keywords'] && $_GET['topic']) == NULL) {
				header("location: index.php");
			}else if (($_GET['keywords'] || $_GET['topic']) == NULL) {
				header("location: index.php");
			}else if (($_GET['keywords'] && $_GET['topic']) != NULL) {
				$search_value = "";
				$count = "";
				if (isset($_GET['keywords'])) {
					if ($_GET['topic'] == "User") {
						$search_value = $_GET['keywords'];
						$search_value = trim($search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {
					$search_for = $search_value;
					$query = "SELECT id,username,first_name,email,gender,verify_id,sign_up_date,activated FROM users where username like '%$search_value%' OR first_name like '%$search_value%' OR email like '%$search_value%'";
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner">No match found!
					</div>';
				}else {
					echo '<div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
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
							<th>Edit User</th>
						</tr>
						<tr>
										';
						while ($row=mysql_fetch_array($query)) {
							$id = $row['id'];
							$fullname = $row['first_name'];
							$username = $row['username'];
							$email = $row['email'];
							$gender = $row['gender'];
							$signupdate = $row['sign_up_date'];
							$verified = $row['verify_id'];
							$activated = $row['activated'];
							?>
							<th><?php echo $id; ?></th>
							<th><?php echo $fullname; ?></th>
							<th><?php echo $username; ?></th>
							<th><?php echo $email; ?></th>
							<th><?php echo $gender; ?></th>
							<th><?php echo $signupdate; ?></th>
							<th><?php echo $verified; ?></th>
							<th><?php echo $activated; ?></th>
							<th class="edituser"><a href="editUsers.php?user=<?php echo $id; ?>">Edit</a></th>
							<?php
							echo '</tr>';
							}
							echo '</table>';
						}
					}
				}else if ($_GET['topic'] == "Post") {
					$search_value = $_GET['keywords'];
					$search_value = trim($search_value);
					$search_value = preg_replace('/[^\p{L}0-9\s]+/u', '-', $search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {

					$search_for = $search_value;
					$query = "SELECT id,body,date_added,added_by,photos,user_posted_to,discription,newsfeedshow,report,note FROM posts where body like '%$search_value%' ORDER BY id DESC";
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner">No match found!
					</div>';
				}else {
					echo '<div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
						<table class="rightsidemenu">
							<tr style="font-weight: bold;" colspan="10" bgcolor="#4DB849">
								<th>Id</th>
								<th>Body</th>
								<th>Date</th>
								<th>Added By</th>
								<th>Posted To</th>
								<th>Discription</th>
								<th>Newsfeed Show</th>
								<th>Report</th>
								<th>Note</th>
								<th>View Post</th>
							</tr>
							<tr>';
						while ($row=mysql_fetch_array($query)) {
								$id = $row['id'];
								$body = substr($row['body'], 0,50);
								$date_added = $row['date_added'];
								$added_by = $row['added_by'];
								$user_posted_to = $row['user_posted_to'];
								$discription = $row['discription'];
								$newsfeedshow = $row['newsfeedshow'];
								$report = $row['report'];
								$note = $row['note'];
							
							 ?>
							 	<th><?php echo $id; ?></th>
								<th style="text-align: left;"><?php echo $body; ?></th>
								<th><?php echo $date_added; ?></th>
								<th><?php echo $added_by; ?></th>
								<th><?php echo $user_posted_to; ?></th>
								<th><?php echo $discription; ?></th>
								<th><?php echo $newsfeedshow; ?></th>
								<th><?php echo $report; ?></th>
								<th><?php echo $note; ?></th>
								<th class="editpost"><a href="viewfullPost.php?post=<?php echo $id; ?>">View</a></th>
							</tr>
							<?php 
						}
						echo '</table>';
						}
					}
				}else if ($_GET['topic'] == "Daowat") {
					$search_value = $_GET['keywords'];
					$search_value = trim($search_value);
					if ($search_value == "") {
					echo '
					<div class="search_banner">Please input something!
						</div>
					';
				}else {
					$search_for = $search_value;
					$query = "SELECT id,body,date_added,added_by FROM daowat where body like '%$search_value%' ORDER BY id DESC";
					$query = mysql_query($query) or die ("could not count");
					$count = mysql_num_rows($query);
				if ($count == 0){
					echo '<div class="search_banner">No match found!
					</div>';
				}else {
					echo '<div class="search_banner">Result for: 
							<span class="search_for">'.$search_value.'</span><br>
							<div class="search_found_num">'.$count.' matches found...</div>
						</div>
						<table class="rightsidemenu">
							<tr style="font-weight: bold;" colspan="10" bgcolor="#4DB849">
								<th>Id</th>
								<th>Body</th>
								<th>Date</th>
								<th>Added By</th>
								<th>View Post</th>
							</tr>
							<tr>';
						while ($row=mysql_fetch_array($query)) {
								$id = $row['id'];
								$body = substr($row['body'], 0,50);
								$date_added = $row['date_added'];
								$added_by = $row['added_by'];
							
							 ?>
							 	<th><?php echo $id; ?></th>
								<th style="text-align: left;"><?php echo $body; ?></th>
								<th><?php echo $date_added; ?></th>
								<th><?php echo $added_by; ?></th>
								<th class="editpost"><a href="viewfullPost.php?post=<?php echo $id; ?>">View</a></th>
							</tr>
							<?php 
						}
						echo '</table>';
						}
					}
				}
			}
		}
			
	?>
	</center>
	</div>
</div>
</body>
</html>
<?php } ?>