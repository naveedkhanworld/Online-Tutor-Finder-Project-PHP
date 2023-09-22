<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="./css/contact.css">
    <link rel="stylesheet" type="text/css" href="css/Navbar.css">
</head>
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
            <li><a href="index.php" > Students</a></li>
            <li><a href="search.php">Search Tutor</a></li>
			<?php 
			if($utype_db == "teacher")
				{

				}else {
					echo '<a class=" navlink" href="postform.php">Post</a>';
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
								echo '<div class="btn"><a href="notification.php"><button class="join-button">Notification'.$resultnoti_cnt.'</button></a>
								<a  href="profile.php?uid='.$user.'"><button class="join-button">'.$uname_db.'</button></a>
								<a  href="logout.php"><button class="join-button" >Logout</button></a>';
							}else{
								echo '<a href="login.php"><button class="join-button">Login</button></a>
								<a href="registration.php"><button class="join-button">Register</button></a><div/>';
							}
						?>
			</div>
			</ul>
	</nav>
</header>
<body style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
<div class="body1">
    <div class="container">
        <div class="content">
          <div class="left-side">
            <div class="address details">
              <i class="fas fa-map-marker-alt"></i>
              <div class="topic">Address</div>
              <br>
              <div class="text-one">GC University Layyah Campus</div>
              <div class="text-two">Layyah City,District Layyah,Punjab,Pakistan</div>
            </div>
            <div class="phone details">
              <i class="fas fa-phone-alt"></i>
              <div class="topic">Phone</div>
              <br>
              <div class="text-one">+92 315 3788537</div>
              <div class="text-two">+92 305 1560920</div>
            </div>
            <div class="email details">
              <i class="fas fa-envelope"></i>
              <div class="topic">Email</div>
              <br>
              <div class="text-one">Naveed Ur Rehman</div>
              <div class="text-two">naveedkhanworld@outlook.com</div>
              <br>
              <div class="text-one">Asim Kamran</div>
              <div class="text-two">ka1702504@gmail.com</div>
            </div>
          </div>
          <div class="right-side">
            <div class="topic-text">Send us a message</div>
            <p>If you have any work from me or any types of quries related to any Subject, you can send me message from here. It's my pleasure to help you.</p>
          <form action="mailto:naveedkhanworld@outlook.com,ka1702504@gmail.com" enctype="text/plain" method="post">
            <div class="input-box">
              <input name="Name " type="text" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
              <input name="E mail Address " type="text" placeholder="Enter your email" required>
            </div>
            <div class="input-box message-box">
              <textarea name="Message " id="" cols="30" rows="10" placeholder="Your Message" required></textarea>
            </div>
            <div class="button">
              <input type="submit" value="Send Now" >
            </div>
          </form>
        </div>
        </div>
      </div>
    </div>
    <script src="./js/script.js"></script>
</body>
</html>