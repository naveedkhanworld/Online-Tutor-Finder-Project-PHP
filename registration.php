
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">
</head>
<?php
$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
}
else {
	header("location: index.php");
}
$u_fname = "";
$u_email = "";
$u_mobile = "";
$u_pass = "";
if (isset($_POST['signup'])) {
//declere veriable
$u_fname = $_POST['full_name'];
$u_email = $_POST['email'];
$u_mobile = $_POST['mobile'];
$u_pass = $_POST['password'];
$u_ac = $_POST['account'];
$u_gender = $_POST['gender'];
$_POST['full_name'] = trim($_POST['full_name']);
	try {
		if(empty($_POST['full_name'])) {
			throw new Exception('Fullname can not be empty');	
		}
		if (is_numeric($_POST['full_name'][0])) {
			throw new Exception('Please write your correct name!');
		}
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty');
		}
		if(empty($_POST['mobile'])) {
			throw new Exception('Mobile can not be empty');	
		}
		if(empty($_POST['password'])) {
			throw new Exception('Password can not be empty');	
		}
		// Check if email already exists
		$check = 0;
		$e_check = $con->query("SELECT email FROM `user` WHERE email='$u_email'");
		$email_check = mysqli_num_rows($e_check);
		if (strlen($_POST['full_name']) >2 && strlen($_POST['full_name']) <16 ) {
			if ($check == 0 ) {
				if ($email_check == 0) {
					if (strlen($_POST['password']) >1 ) {
						$d = date("Y-m-d"); //Year - Month - Day
						$u_fname = ucwords($_POST['full_name']);
						$u_email = mb_convert_case($u_email, MB_CASE_LOWER, "UTF-8");
						$u_pass = md5($_POST['password']);
						$confirmCode   = mt_rand(100000, 999999);
						// send email
						$msg = "
						Assalamu Alaikum...
						Your activation code: ".$confirmCode."
						Signup email: ".$_POST['email']."
						";
						$sql = "INSERT INTO `user` (`fullname`,`gender`,`email`,`phone`,`pass`,`type`,`confirmcode`) VALUES ('".$u_fname."','".$u_gender."','".$u_email."','".$u_mobile."','".$u_pass."','".$u_ac."','".$confirmCode."')";
						if($con->query($sql)){
							//success message
						$success_message = '
						<div class="signupform_content"><h1><font face="bookman">Registration successfull!</font></h1>
						<div class="signupform_text" style="font-size: 30px; text-align: center;">
						<font face="bookman">
							Your Email: '.$u_email.'<br>
						</font></div></div>';
						}else{
							echo "Error: " . $sql . "<br>" . $con->error;
						}
					}else {
						throw new Exception('Make strong password!');
					}
				}else {
					throw new Exception('Email already taken!');
				}
			}else {
				throw new Exception('Username already taken!');
			}
		}else {
			throw new Exception('Firstname must be 2-15 characters!');
		}
	}
	catch(Exception $e) {
		$error_message = $e->getMessage();
	}
}
?>
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
            <li><a href="index.php" > Students</a></li>
            <li><a href="search.php">Search Tutor</a></li>
			<li><a class="navlink" href="postform.php">Post</a></li>
						<?php
								echo'<a href="login.php"><button class="join-button">Login</button></a>
								<a href="registration.php"><button class="join-button">Register</button></a><d/iv></li>';
						?>
		</ul>
</nav>
<body style="  background:fixed url(./image/bg1.jpg);background-size: 100%;" class="">
<div class="nbody">
			<?php
				echo '<center><div class="main"> 
				<form class="form" action="" method="post"><h1 class="warning" style="color:yellow;  padding-top: 18px; font-size: 25px;">';	
						if (isset($error_message)) {echo '⚠️'.$error_message.'⚠️';}	
				echo'</h1>';
				if(isset($success_message)) {echo $success_message;}
				else echo'
					<h1>Register New Account</h1>
					<div class="ac_type">
						<input type="radio" value="teacher" id="radioOne" name="account" checked><p>As a Teacher</p>
						<input type="radio" value="student" id="radioTwo" name="account"><p>As a Student</p>
					</div>
					<input type="text" name="full_name" id="name" placeholder="Enter Your Full Name" value="'.$u_fname.'" required>
					<input type="email" id="name" name="email" placeholder="Enter Your Email Address" value="'.$u_email.'"  required>
					<input type="number" name="mobile" id="name"  placeholder="Enter Phone number" value="'.$u_mobile.'" required>
					<input type="password" name="password" id="password" placeholder="Create Password" required>
					<input type="password" name="password" id="confirm_password" placeholder="Confirm Your Password" required>
					<div class="gender">
						<p id="genderp">Select Gender: </p>
						<input type="radio" name="gender" id="male" value="male" checked><p>Male</p>
						<input type="radio" name="gender" id="female" value="female"><p>Female</p>
					</div>
					<input type="submit" name="signup" id="submit" value="Register Now" required/>
				</form>
			</div>
		</center> '
			?>
</div>
<script src="./js/cpswd.js"></script>
</body>
</html>