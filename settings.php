<?php
 $con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_name = $result->fetch_assoc();
			$uname_db = $get_user_name['fullname'];
			$email_db = $get_user_name['email'];
			$pro_pic_db = $get_user_name['user_pic'];
			$ugender_db = $get_user_name['gender'];
			$utype_db = $get_user_name['type'];

			if($pro_pic_db == ""){
				if($ugender_db == "male"){
					$pro_pic_db = "malepic.png";
				}else if($ugender_db == "female"){
					$pro_pic_db = "femalepic.png";

				}
			}
}

$error = "";

$senddata = @$_POST['changesettings'];
//password variable
$oldpassword = strip_tags(@$_POST['opass']);
$newpassword = strip_tags(@$_POST['npass']);
$repear_password = strip_tags(@$_POST['npass1']);
$email = strip_tags(@$_POST['email']);
$oldpassword = trim($oldpassword);
$newpassword = trim($newpassword);
$repear_password = trim($repear_password);
//update pass
if ($senddata) {
	//if the information submited
	$password_query = $con->query("SELECT * FROM user WHERE id='$user'");
	while ($row = mysqli_fetch_assoc($password_query)) {
		$db_password = $row['pass'];
		$db_email = $row['email'];
		//try to change MD5 pass
		$oldpassword_md5 = md5($oldpassword);
		if ($oldpassword_md5 == $db_password) {
			if ($newpassword == $repear_password) {
				//Awesome.. Password match.
				$newpassword_md5 = md5($newpassword);
				if (strlen($newpassword) <= 3) {
					$error = "<p class='error_echo'>Sorry! But your new password must be 3 or more then 5 character!</p>";
				}else {
				$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
				$password_update_query = $con->query("UPDATE user SET pass='$newpassword_md5', confirmcode='$confirmCode', email='$email' WHERE id='$user'");
				$error = "<p class='succes_echo'>Success! Your settings updated.</p>";
			}
		}else {
				$error = "<p class='error_echo'>Two new password don't match!</p>";
			}
	}else {
			$error = "<p class='error_echo'>The old password is incorrect!</p>";
		}
}
}else {
	$error = "";
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>Porfile Setting</title>
	<!-- menu tab link -->
	<link rel="stylesheet" href="css/Navbar.css">
	<link rel="stylesheet" href="css/setting.css">	
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
<div class="main">
<center><div class="zzz">
	<!-- Students -->
	<div class="bars">
            <?php echo '<a href="aboutme.php?uid='.$user.'" ><button class="navbtn p_btn">Profile</button></a>'; ?>
            <?php echo '<a href="profile.php?uid='.$user.'" ><button class="navbtn">Post</button></a>'; ?>
            <?php echo '<a href="settings.php"><button class="navbtn s_btn">Settings</button></a>'; ?>
        </div>
		<div class="setting-box">
                <form action="" method="POST" class="registration">
                        <div class="signup_error_msg">
                            <?php echo $error; ?>
							
                        </div>
                        <text>Change Password:</text><br>
                        <input class="" type="password" name="opass" placeholder="Old Password">
                        <input class="" type="password" id="password" name="npass" placeholder="New Password">
                        <input class="" type="password" id="confirm_password" name="npass1" placeholder="Repeat Password">
                        <h3 >Change Email:</h3>
                        <?php echo '<input class="nm" required type="email" name="email" placeholder="New Email" value="'.$email_db.'">'; ?>
                        <input class="sub_button" id="bt" type="submit" name="changesettings" value="Update Settings">
                </form>
        </div></center>
</div>
<script src="js/script.js"></script>
<script src="js/cpswd.js"></script>
</body>
</html>