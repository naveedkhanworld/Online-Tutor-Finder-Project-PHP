<!DOCTYPE html>
<html>
<head>
	<title>About</title>
	<link rel="stylesheet" href="css/setting.css">
	<link rel="stylesheet" href="css/Navbar.css">
</head>
<?php

$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}

?>
<?php 

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

	$rslttution = $con->query("SELECT * FROM applied_post WHERE (post_by='$user2' AND applied_by='$user' AND tution_cf='1') OR applied_by='$user2' AND applied_to='$user'");

	$cnt_rslttution = $rslttution->num_rows;

	
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
								<a href="registration.php"><button class="join-button">Register</button></a></div></li>';
							}
						?>
			</div>
			</ul>
	</nav>
</header>
<center><body class="body1" style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
<!-- Students -->
	<div class="main">
        <div class="zzz">
            <!-- Students -->
				<?php 
						if($user == $user2){
							echo '<div class="bars">
							<a href="aboutme.php?uid='.$user.'" ><button class="navbtn p_btn s_btn">Profile</button></a>
							<a href="profile.php?uid='.$user.'" ><button class="navbtn">Post</button></a>
							<a href="settings.php"><button class="navbtn ">Settings</button></a>
							</div>';
							
						}else{
						}
					?>	

					<?php
                        error_reporting(0);
						$query1 = $con->query("SELECT * FROM user WHERE id='$user2'");
						$user_fname = $query1->fetch_assoc();
						$uname_db = $user_fname['fullname'];
						$pro_pic_db = $user_fname['user_pic'];
						$ugender_db = $user_fname['gender'];
						$uemail_db = $user_fname['email'];
						$uphone_db = $user_fname['phone'];
						$ugender_db = $user_fname['gender'];
						$uaddress_db = $user_fname['address'];
						$utype_db = $user_fname['type'];

						$query2 = $con->query("SELECT * FROM tutor WHERE t_id='$user2' ORDER BY id DESC");
						$tutor_info = $query2->fetch_assoc();
						$uinsname_db = $tutor_info['inst_name'];
						$umedium_db = $tutor_info['medium'];
						$usalrange_db = $tutor_info['salary'];
						$uclass_db = $tutor_info['class'];
						$upresub_db = $tutor_info['prefer_sub'];
						$upreloca_db = $tutor_info['prefer_location'];

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
						<div class="setting-box" >
						<div class="nfbody">
						<center><div class="p_body">';
							if($user == $user2){
								echo '<a href="updateinfo.php" style="color:blue; font-size:15px">Edit profile information</a>';
							}
						
							echo '<div class="photo"  style=" margin-top:10px">';
								if (file_exists('image/profilepic/'.$pro_pic_db.'')){
									echo '<img src="image/profilepic/'.$pro_pic_db.'" width="100" hieght="100" style="border-radius:100%;" class="home-prodlist-imgi">';
								}else {
									
									echo'<div class="home-prodlist-imgi"padding: 0 0 6px 0;">No Image Found!</div>';
								}
							echo '</div><h4 style="font-size: 12px;">Account Type: '.ucfirst($utype_db).'</h4>
							<h2>Personal Informaion</h2>
							<div class="itemrow">
					  				<label>Name: </label>
					  				<span>'.$uname_db.'</span>
				  			</div><div class="itemrow">
					  				<label>Email: </label>
					  				<span>'.$uemail_db.'</span>
				  			</div> 
							<div class="itemrow">
					  				<label>Phone: </label>
					  				<span>'.$uphone_db.'</span>
				  			</div>
							<div class="itemrow">
					  				<label>Address: </label>
					  				<span>'.$uaddress_db.'</span>
				  			</div><div class="itemrow">
					  				<label>Gender: </label>
					  				<span>'.ucfirst($ugender_db).'</span>
				  			</div>';
								if($utype_db == "student"){

								}else if($utype_db == "teacher"){
									echo '<div class="itemrow">
					  				<label>Institute: </label>
					  				<span>'.$uinsname_db.'</span>
				  			</div>';
								}
							 echo'</div></div><p><br><p>';
							if($utype_db == "student")
							{

							}else if($utype_db == "teacher"){
								echo '<div class="nfbody">
						<div class="p_body">';
							if($user == $user2){
								echo '<div style="margin-top:20px;">
								<a href="updatetutioninfo.php?uid='.$user.'" style="color:blue; font-size:15px">Edit Tution Infotmation</a>
							</div>';
							}
							echo '<h2 style="text-align: center;">Tution Informaion</h2>
							<table class="table-data" border="1">
                            <tr>
                            <th>Prefer Subject:</th> <td>'.$upresub_db.'</td>
                            </tr>
                            <tr>
                            <th>Class:</th> <td>'.$uclass_db.'</td>
                            </tr>
                            <tr>
                            <th>Medium:</th><td>'.$umedium_db.'</td>
                            </tr>
                            <tr>
                            <th>Salary:</th>  <td>'.$usalrange_db.'</td>
                            </tr>
                            <tr>
                            <th>Location:</th> <td>'.$upreloca_db.'</td>
                            </tr>
                            </table><p><br></p>
							';
							}
						echo '</div></div>
					</div>';
				?>
		</div>
	</div>
	<script src="./js/script.js"></script>
</body></center>
</html>