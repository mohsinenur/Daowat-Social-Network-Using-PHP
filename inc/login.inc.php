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
?>

<?php 


if (isset($_POST['login'])) {
		header('location: index.php');
	}
?>


<div class="headerLogin">
	<div class="login_menubar clearfix">
		<div class="menu_logo">
			<h1>
				<a title="Go to Daowat Home" href="index.php">
					<b>daowat</b>
				</a>
			</h1>
		</div>
		<div class="menu_login_container">
			<form action="index.php" method="POST">
				<table class="menu_login_container">
					<tr class="login_">
						<td>
						<?php
							if (isset($user)) {
							echo '<input type="submit" name="login" class="uiloginbutton" style="margin-top: 17px;" value="Back to home">';
						}else {
							echo '
							<input type="submit" name="login" class="uiloginbutton" style="margin-top: 17px;" value="Log In / Sign Up">
							';
							
							}
						?>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>