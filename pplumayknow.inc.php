

<?php 


//gettting user gender
$get_user_gender = mysql_query("SELECT * FROM users WHERE username='$user'");
$gender_user_row = mysql_fetch_assoc($get_user_gender);
$user_gender_value = $gender_user_row['gender'];
if($user_gender_value == 1) {
	$get_ppl_info = mysql_query("SELECT * FROM users WHERE username!='$user' AND gender='1' AND activated='1' AND blocked_user='0' ORDER BY  RAND() ");
	//declear variable
	$getuserNum= '0';
	if($get_ppl_info === FALSE) { 
	    die(mysql_error()); // TODO: better error handling
	}

	while ($row_user = mysql_fetch_assoc($get_ppl_info)) {
			
			$user_name= $row_user['username'];
			//if follow	
			$if_user_to_follow = mysql_query("SELECT * FROM follow WHERE (user_from='$user' AND user_to='$user_name')");
			$count_user_to_follow = mysql_num_rows($if_user_to_follow);
			if ($count_user_to_follow == 0) {
			  	$profile_pic_db= $row_user['profile_pic'];
				$user_name_f = $row_user['first_name'];
	
				//check for propic delete
				$pro_changed = mysql_query("SELECT * FROM posts WHERE added_by='$user_name' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
				$get_pro_changed = mysql_fetch_assoc($pro_changed);
				$pro_num = mysql_num_rows($pro_changed);
				if ($pro_num == 0) {
					$profile_pic = "img/default_propic.png";
				}else {
					$pro_changed_db = $get_pro_changed['photos'];
				if ($pro_changed_db != $profile_pic_db) {
					$profile_pic = "/img/default_propic.png";
				}else {
					$profile_pic = "./userdata/profile_pics/".$profile_pic_db;
				}
				}
	
				echo "
					<form method='POST' action=''>
					<div style='display: flex; padding: 8px 0;'> ";
							echo "<div>
							<img src='$profile_pic' style= 'border-radius: 4px' border: 1px solid #ddd; title=\"$user_name_f\" height='70' width='65'  />
							</div>";
						
						echo "<div style='margin-left: 10px;'><b><a href='profile.php?u=$user_name' style='text-decoration: none; font-size: 14px; color: #0B810B;' title=\"Go to $user_name_f's Profile\" class='posted_by'>$user_name_f</a></b> <br><br>
						
						<b><a href='profile.php?u=$user_name' style='text-decoration: none; margin: 0px;' class='frndPokMsg' title='View Full Profile' >View Profile</a></b>
						</div>";
					echo "
					</div>
					</form>
					";	
	
				$getuserNum++;
	
				if ($getuserNum == 3){
					break;
				}
			}
			
			//follow request system
			if (@($_POST[''.$user_name.''])) {
				header("location: profile.php?u=".$user_name."");
			}
			
		}






}else if($user_gender_value == 2) {
	$get_user = mysql_query("SELECT * FROM users WHERE gender='2'");

}



?>