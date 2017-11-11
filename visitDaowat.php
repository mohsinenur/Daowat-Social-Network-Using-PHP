<?php include ( "./inc/connect.inc.php" ); ?>
<?php

//check for userfrom propic delete

	$cover_pic= "img/default_covpic.png";

//Check whether the user has uploaded a profile pic or not

	$profile_pic= "img/default_propic.png";


?>

<!DOCTYPE html>
<html>
<head>
	<title>Daowat</title>
	<link rel="icon" href="./img/title.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('body').on('keydown', '#post', function(e) {
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
		<div style="width: 900px; margin: 52px auto;">
			<div style="float: left;">
				<div class="homeLeftSideContent" >
					<div class="home_cov" style= "background: url(<?php echo $cover_pic; ?>) repeat center center;">
						<div style="float: left;">
							<img src="<?php echo $profile_pic; ?>" height="70" width="70" style="border-radius: 40px; margin: 20px 0 0 10px;border: 2px solid #fff;" />
						</div>
						<div class="home_cov_data">
							<a href="profile_update.php" class="home_cov_nm" >Edit your profile</a><br>
						</div><br>
						<div class="homenavemanu">
							<div >
								<div ><a href="visitDaowat.php" style="color: #0B810B">Daowat</a></div>
								<div ><a href="signin.php">Newsfeed</a></div>
								<div ><a href="signin.php">Me</a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="settingsleftcontent" style="width: 301px;  margin-top: 15px;">
					<?php include './inc/profilefooter.inc.php'; ?>
				</div>
			</div>
			<div style="float: right;">
				<div class="profilePosts">
					<?php

					//timeline query table
					$getposts = mysql_query("SELECT * FROM daowat ORDER BY id DESC LIMIT 10") or die(mysql_error());
					if (mysql_num_rows($getposts)) {
					echo '<ul id="recs">';
					while ($row = mysql_fetch_assoc($getposts)) {
						include ( "./inc/getDaowatpost.inc.php" );
					}
					echo '<li class="getmore" ><a href="signin.php">Show More</a></li>';
					echo '</ul>';
					}
					echo '
			</div>
		</br>
	</div>
</div>
</div>
</div>
</div>';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.getmore').live('click',function() {
				var lastid = $(this).attr('id');
				$.ajax({
					type: 'GET',
					url: 'showmorenext.php',
					data: 'lastid='+lastid,
					beforeSend: function() {
						$('.getmore').html('Loading....');
					},
					success: function(data) {
						$('.getmore').remove();
						$('#recs').append(data);
					}
				});
			});
		});
	</script>
</body>
</html>