<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
	<link rel="stylesheet" type="text/css" href="css/Setting.css">
	
</head>
<body class="body1" style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
<?php

$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header("location: index.php");
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['fullname'];
			$uemail_db = $get_user_email['email'];
			$utype_db = $get_user_email['type'];
}
if (isset($_REQUEST['uid'])) {
	$user2 = mysqli_real_escape_string($con, $_REQUEST['uid']);
	if($user != $user2){
		header('location: index.php');
	}
}else {
	header('location: index.php');
}
//time ago convert
include_once("inc/timeago.php");
$time = new timeago();
?>
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
	<!-- Students -->
	<div class="main">
        <center><div class="zzz">
		<div class="bars">
            <?php echo '<a href="aboutme.php?uid='.$user.'" ><button class="navbtn p_btn">Profile</button></a>'; ?>
            <?php echo '<a href="profile.php?uid='.$user.'" ><button class="navbtn s_btn">Post</button></a>'; ?>
            <?php echo '<a href="settings.php"><button class="navbtn ">Settings</button></a>'; ?>
        </div>
		</div>
					<div class="holecontainer">
						<?php
					$query = $con->query("SELECT * FROM post WHERE postby_id='$user' ORDER BY id DESC");
					$num1 = mysqli_num_rows($query);
					if($num1 == 0){
						echo '
						<div class="p_bodyn">
							<p>No post found !!!</p>
						</div>';
					}
					while ($row = $query->fetch_assoc()) {
						$post_id = $row['id'];
						$postby_id = $row['postby_id'];
						$sub = $row['subject'];
						$class = $row['class'];
						$salary = $row['salary'];
						$location = $row['location'];
						$p_university = $row['p_university'];
						$post_time = $row['post_time'];
						$deadline = $row['deadline'];
						$medium = $row['medium'];	

						$query1 = $con->query("SELECT * FROM user WHERE id='$postby_id'");
						$user_fname = $query1->fetch_assoc();
						$uname_db = $user_fname['fullname'];
						$pro_pic_db = $user_fname['user_pic'];
						$ugender_db = $user_fname['gender'];

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
							<div class="setting-box">
							<center>
							<div class="profile">
								<div>
									<a href="editpost.php?pid='.$post_id.'"height="25" width="25" ></a>
								</div>
								<div class="pic">
									<img src="image/profilepic/'.$pro_pic_db.'" width="150px" height="150px" style="border-radius:100%;">
								</div>
								<div class="pro_dt">
									<h2>'.$uname_db.'</h2>
									<h5 style="color: #757575;">'.$time->time_ago($post_time).' &nbsp;|&nbsp; Deadline: '.$deadline.'</h5>
								</div>
								<table class="table-data" border="1">
									<tr>
									<th>Subject:</th> <td>'.$sub.'</td>
									</tr>
									<tr>
									<th>Class:</th> <td>'.$class.'</td>
									</tr>
									<tr>
									<th>Medium:</th><td>'.$medium.'</td>
									</tr>
									<tr>
									<th>Salary:</th>  <td>'.$salary.'</td>
									</tr>
									<tr>
									<th>Location:</th> <td>'.$location.'</td>
									</tr>
									<tr>
									<th>University:</th><td>'.$p_university.'</td>
									</tr>
									</table><p><br><p>
							</div>
						</center>
					</div>';
					}
				?>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- footer -->
	<div>

	




</div>
<script src="./js/script.js"></script>
</body>

</html>