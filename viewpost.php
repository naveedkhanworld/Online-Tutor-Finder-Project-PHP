<?php
$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}

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

if (isset($_REQUEST['pid'])) {
	$pstid = mysqli_real_escape_string($con, $_REQUEST['pid']);
}else {
	header('location: index.php');
}

//apply post
if (isset($_POST['post_apply'])) {
	if($user == ''){
		$_SESSION['apply_post'] = "".$pstid."";
		header("Location: login.php?pid=".$pstid."");
	}else{
		$resultpost = $con->query("SELECT * FROM post WHERE id='$pstid'");
		$get_user_name = $resultpost->fetch_assoc();
			$postby_id = $get_user_name['postby_id'];

		$result = mysqli_query($con, "INSERT INTO applied_post (`post_id`,`post_by`,`applied_by`,`applied_to`) VALUES ('".$pstid."','".$postby_id."','".$user."','".$postby_id."')");

		if($result){
			header("Location: viewpost.php?pid=".$pstid."");
		}else{
			echo "Could not apply!";
		}
	}
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>View Post</title>
	<link rel="stylesheet" type="text/css" href="css/vpost.css">
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
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
<body class="body1" style="background:fixed url(./image/bg1.jpg);background-size: 100%;">
	<!-- Students -->
	<center><div class="nbody">
		<div class="nfeedleft">
				<?php
					$todaydate = date("m/d/Y"); //Month/Day/Year 12/20/2023
					$query = $con->query("SELECT * FROM post WHERE id= '$pstid'");
					while ($row = $query->fetch_assoc()) {
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
							<div class="nfbody">
							<div class="p_head">
							<div>';
								if($user!='' && $utype_db=='student'){
										echo "<h4 style='color: red;'>Only teacher can apply!</h4><br>";
								}else {
									if((strtotime($deadline) - strtotime($todaydate)) < 0){
										echo '
										<input type="submit" class="sub_button" name="" value="Deadline Over" />';
									}else{
										$resultpostcheck = $con->query("SELECT * FROM applied_post WHERE post_id='$pstid' AND applied_by='$user'");
											$query_apply_cnt = $resultpostcheck->num_rows;
											if($query_apply_cnt > 0){
												echo '
											<input type="button" class="sub_button" name="" value="Already Applied" />';
											}else{
											echo '<form action="" method="post">
											<input type="submit" class="sub_button" name="post_apply" style="margin-bottom: 10px; background:yellow; color:black; cursor:pointer;" value="Confirm Apply" />
										</form>';
												}
										
									}
								}
							echo '<br></div>
							<div class="prof-pic">
								<img  src="image/profilepic/'.$pro_pic_db.'" width="80px" height="80px">
							</div><br>
							<div class="p_nmdate">
								<h4>'.$uname_db.'</h4><br>
								<h5 style="color: #757575;">'.$time->time_ago($post_time).' &nbsp;|&nbsp; Deadline: '.$deadline.'</h5><br>
							</div>
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
						</table>
					</div>';
					}
				?>
		</div></center>
	</div>
	<script src="./js/script.js"></script>
</body>
</html>