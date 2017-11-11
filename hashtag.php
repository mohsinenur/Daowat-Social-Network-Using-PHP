<?php include ( "./inc/connect.inc.php" ); ?>
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

<!DOCTYPE html>
<html>
<head>
	<title>Search • Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
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
<?php include ( "./inc/header.inc.php" ); ?>
<div class="search_body">
<center>
	<div class="search_nav">
		<ul class="search_nav_menu"></ul>
	</div>
	<?php
	if(isset($_GET["tag"])){
		$tag = preg_replace('#[^a-z0-9_]#i', '', $_GET["tag"]);
		// $tag is now sanitized and ready for database queries here
		$fulltag = "#".$tag;
		$query = "SELECT id,body,date_added,added_by,photos,user_posted_to,discription FROM posts where body like '%$fulltag%' ORDER BY id DESC";
		$query = mysql_query($query) or die ("could not count");
		$count = mysql_num_rows($query);
		if ($count == 0){
			echo '<div class="search_banner">No match found!
			</div>';
		}else {
			echo '<div class="search_banner">Result for: 
				<span class="search_for">'.$fulltag.'</span><br>
				<div class="search_found_num">'.$count.' matches found...</div>
			</div>
			<div class="search_result_container">
				';
			while ($row=mysql_fetch_array($query)) {
					include ( "./inc/getProfilepost.inc.php" );
				}
			}
	}

	?>
</center>
</div>

</body>
</html>