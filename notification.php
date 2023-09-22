<?php
 include ( "inc/connection.inc.php" );

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
	$utype_db = "";
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_name = $result->fetch_assoc();
			$uname_db = $get_user_name['fullname'];
			$utype_db = $get_user_name['type'];
}

if($utype_db == "student"){
	$up = $con->query("UPDATE applied_post SET student_ck='yes'");
}
if($utype_db == "teacher"){
	$up = $con->query("UPDATE applied_post SET tutor_ck='yes'");
}

//time ago convert
include_once("inc/timeago.php");
$time = new timeago();

?>



<!DOCTYPE html>
<html>
<head>
	<title>Notisfications</title>
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
	<link rel="stylesheet" type="text/css" href="css/Notis.css">
</head>
<header>
<nav>
		  <div class="logo">
            <img src="./Image/logo.png" alt="Logo Image">
        </div>
        <div class="hamburger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" >Students</a></li>
            <li><a href="search.php">Search Tutor</a></li>
			<?php 
			if($utype_db == "teacher")
				{

				}else {
					echo '<li><a class=" navlink" href="postform.php">Post</a></li>';
				}
			 ?>
						<?php
							if($user != ""){
								$resultnoti = $con->query("SELECT * FROM applied_post WHERE post_by='$user' AND student_ck='no'");
								$resultnoti_cnt = $resultnoti->num_rows;
								if($resultnoti_cnt == 0){
									$resultnoti_cnt = "";
								}else{
									$resultnoti_cnt = '('.$resultnoti_cnt.')';
								}
								echo '<div class="btn"><li><a href="notification.php"><button class="join-button">Notification'.$resultnoti_cnt.'</button></a>
								<a  href="profile.php?uid='.$user.'"><button class="join-button">'.$uname_db.'</button></a>
								<a  href="logout.php"><button class="join-button" >Logout</button></a>';
							}else{
								echo '<a href="login.php"><button class="join-button">Login</button></a>
								<a href="registration.php"><button class="join-button">Register</button></a><d/iv></li>';
							}
						?>
			</div>
			</ul>
	</nav>
</header>
<body class="body1" style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
	<!-- Students -->
	<center><div class="nbody">
		<div class="nfeedleft">
				<?php
					$todaydate = date("m/d/Y"); //Month/Day/Year 8/20/2023
					if($utype_db == 'teacher'){
						$query = $con->query("SELECT * FROM applied_post WHERE (applied_by='$user' AND tution_cf='1') OR (applied_to='$user') ORDER BY id DESC");
						$nmrow = $query->num_rows;
						if($nmrow == 0){
							echo '
								<div class="nfbody">
								<div class="p_head">
								<h1 style="background:red;">Notifications</h1>
								<div class="p_nmdate">
									<h2>No Notification Found!</h2>
								</div>
							</div>
						</div>';
						}else{
						while ($row = $query->fetch_assoc()) {
							$post_id = $row['post_id'];
							$applied_by_id = $row['applied_by'];
							$post_by_id = $row['post_by'];
							$deadline = $row['applied_time'];
							$tution_confirm = $row['tution_cf'];

							if($post_by_id == 0) $post_by_id = $applied_by_id;

							$query1 = $con->query("SELECT * FROM user WHERE id='$post_by_id'");
							$user_fname = $query1->fetch_assoc();
							$uname_db = $user_fname['fullname'];
							$pro_pic_db = $user_fname['user_pic'];
							$ugender_db = $user_fname['gender'];

							if($post_id == ""){
								$notimsg = "applied on your tution! ";
							}else{
								$notimsg = "Choose you as tutor! ";
							}
						

							if($pro_pic_db == ""){
									if($ugender_db == "male"){
									$pro_pic_db = "malepic.png";
								}else if($ugender_db == "female"){
									$pro_pic_db = "femalepic.png";

								}
							}else {
								if (file_exists("image/profilepic/".$pro_pic_db)){
								//nothing
								}else{
										if($ugender_db == "male"){
										$pro_pic_db = "malepic.png";
									}else if($ugender_db == "female"){
										$pro_pic_db = "femalepic.png";

									}
								}
							}



							echo '
								<div class="nfbody">
								<div class="p_head">
								<div style="float: right;">';
										
												echo '<form action="aboutme.php?uid='.$post_by_id.'" method="post">
										<input type="submit" class="sub_button"  style="margin: 0px;" name="" value="View Contact" />
									</form>';
								echo '</div>
								<div>
									<img src="image/profilepic/'.$pro_pic_db.'" width="41px" height="41px">
								</div>
								<div class="p_nmdate">
									<h4><a href="aboutme.php?uid='.$post_by_id.'" style="color: #087E0D;">'.$uname_db.'</a> <span style="color: #626262; font-weight: 100;">'.$notimsg.'</span></h4>
									<h5 style="color: #757575;"><a class="c_ptime" >'.$time->time_ago($deadline).'</a></h5>
								</div>
							</div>
						</div>';



						}
						}
						
					}elseif($utype_db == 'student'){
						$query = $con->query("SELECT * FROM applied_post WHERE post_by='$user' ORDER BY id DESC");
						while ($row = $query->fetch_assoc()) {
							$post_id = $row['post_id'];
							$applied_by_id = $row['applied_by'];
							$deadline = $row['applied_time'];
							$tution_confirm = $row['tution_cf'];

							$query1 = $con->query("SELECT * FROM user WHERE id='$applied_by_id'");
							$user_fname = $query1->fetch_assoc();
							$uname_db = $user_fname['fullname'];
							$pro_pic_db = $user_fname['user_pic'];
							$ugender_db = $user_fname['gender'];

							if($post_id == ""){
								$notimsg = "applied on your tution ";
							}else{
								$notimsg = "want to teach you! ";
							}

							if($pro_pic_db == ""){
									if($ugender_db == "male"){
									$pro_pic_db = "malepic.png";
								}else if($ugender_db == "female"){
									$pro_pic_db = "femalepic.png";

								}
							}else {
								if (file_exists("image/profilepic/".$pro_pic_db)){
								//nothing
								}else{
										if($ugender_db == "male"){
										$pro_pic_db = "malepic.png";
									}else if($ugender_db == "female"){
										$pro_pic_db = "femalepic.png";

									}
								}
							}



							echo '
								<div class="nfbody">
								<div class="p_head">
								<div style="float: right;">';
										
											
												if($tution_confirm == "1"){
													echo '
												<input type="button" class="sub_button" style="margin: 0px; background-color: #a76d6d; cursor: default;" name="" value="Already Confirm" />';
												}else{
												echo '<form action="approvetutor.php?app_tut='.$post_id.'" method="post">
										<input type="submit" class="sub_button"  style="margin: 0px;" name="" value="Confirm" />
									</form>';
										}
								echo '</div>
								<div>
									<img src="image/profilepic/'.$pro_pic_db.'" width="41px" height="41px">
								</div>
								<div class="p_nmdate">
									<h4><a href="aboutme.php?uid='.$applied_by_id.'" style="color: #087E0D;">'.$uname_db.'</a> <span style="color: #626262; font-weight: 100;">'.$notimsg.'</span></h4>
									<h5 style="color: #757575;"><a class="c_ptime" href="viewpost.php?pid='.$post_id.'">'.$time->time_ago($deadline).'</a></h5>
								</div>
							</div>
						</div>';



						}
					}
				?>
		</div>
	</div>


</div></center>
<script src="./js/script.js"></script>
</body>
</html>
