<?php
include ( "inc/connection.inc.php" );
error_reporting(0);
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
			$uphone_db = $get_user_name['phone'];
			$pro_pic_db = $get_user_name['user_pic'];
			$uaddress_db = $get_user_name['address'];
			$ugender_db = $get_user_name['gender'];
			$utype_db = $get_user_name['type'];
			$result1 = $con->query("SELECT * FROM tutor WHERE t_id='$user' ORDER BY id DESC");
		$get_tutor_name = $result1->fetch_assoc();
			$uinst_db = $get_tutor_name['inst_name'];
			$umedium_db = $get_tutor_name['medium'];
			$usalrange_db = $get_tutor_name['salary'];            
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
}


//update pic
if (isset($_POST['updatepic'])) {
	//finding file extention
$profile_pic_name = @$_FILES['profilepic']['name'];
if($profile_pic_name == ""){
	if($result = $con->query("UPDATE user SET phone='$_POST[phone]', address='$_POST[address]' WHERE id='$user'")){
				$succs_message = "Information Updated.";
		}
		if($utype_db == "teacher"){
				if($result = $con->query("UPDATE tutor SET inst_name='$_POST[inst_nm]' WHERE t_id='$user'")){
					$succs_message = "Informaion Updated!";
				}
			}
		header("Location: aboutme.php?uid=".$user."");

}else{
	$file_basename = substr($profile_pic_name, 0, strripos($profile_pic_name, '.'));
$file_ext = substr($profile_pic_name, strripos($profile_pic_name, '.'));
if (((@$_FILES['profilepic']['type']=='image/jpeg') || (@$_FILES['profilepic']['type']=='image/png') || (@$_FILES['profilepic']['type']=='image/jpg') || (@$_FILES['profilepic']['type']=='image/gif')) && (@$_FILES['profilepic']['size'] < 1000000)) {
	if (file_exists("image/profilepic")) {
		//nothing
	}else {
		mkdir("image/profilepic");
	}
	
	
	$filename = strtotime(date('Y-m-d H:i:s')).$file_ext;
	if (file_exists("image/profilepic/".$filename)) {
		$error_message = @$_FILES["profilepic"]["name"]."Already exists";
	}else {
		if(move_uploaded_file(@$_FILES["profilepic"]["tmp_name"], "image/profilepic/".$filename)){
			$photos = $filename;
			if($result = $con->query("UPDATE user SET phone='$_POST[phone]', address='$_POST[address]', user_pic='$photos' WHERE id='$user'")){
				$delete_file = unlink("image/profilepic/".$pro_pic_db);
				$succs_message = "Informaion Updated!";
			}
			if($utype_db == "teacher"){
				if($result = $con->query("UPDATE tutor SET inst_name='$_POST[inst_nm]' WHERE t_id='$user'")){
					$succs_message = "Informaion Updated!";
				}
			}
				header("Location: aboutme.php?uid=".$user."");
		}else {
			$error_message = "File can't move!!!";
		}
		//echo "Uploaded and stored in: userdata/profile_pics/$item/".@$_FILES["profilepic"]["name"];
		
		
	}
	}
	else {
		$error_message = "Choose a picture!";
	}
}

}


?>



<!DOCTYPE html>
<html>
<head>
	<title>Update Profile</title>
	<link rel="stylesheet" type="text/css" href="css/setting.css">
    <link rel="stylesheet" type="text/css" href="css/Navbar.css">
</head>
<body style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
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
	<!-- Students -->
	<div class="main">
        <center><div class="zzz">
		<div class="bars">
            <?php echo '<a href="aboutme.php?uid='.$user.'" ><button class="navbtn p_btn s_btn" >Profile</button></a>'; ?>
            <?php echo '<a href="profile.php?uid='.$user.'" ><button class="navbtn ">Post</button></a>'; ?>
            <?php echo '<a href="settings.php"><button class="navbtn ">Settings</button></a>'; ?>
        </div>
		</div>
				<div class="">
								<form action="" method="POST" class="registration" enctype="multipart/form-data">
								<?php 
									echo '
										<div class="holecontainer">
										<div class="setting-box">
										<div class="p_body">';
													if (isset($error_message)) {
														echo '<div class="signup_error_msg" style="
  color: #A92A2A;">'.$error_message.'</div>';
													}elseif(isset($succs_message)){
														echo '<div class="signup_error_msg" style="
  color: #A92A2A;">'.$succs_message.'</div>';
													}
												echo'
											<div class="" style="text-align: center; padding-right: 26px;">';
												if (file_exists('image/profilepic/'.$pro_pic_db.'')){
													echo '<img src="image/profilepic/'.$pro_pic_db.'" width="150" hieght="150" style="border-radius:100%;" class="home-prodlist-imgi">';
												}else {
													
													echo'<div class="home-prodlist-imgi" style="text-align: center; padding: 0 0 6px 0;">No Image Found!</div>';
												}
											echo '</div><div style="text-align: center;"><input type="file" name="profilepic" value="Choose"/></div></br>
											<h2 style="text-align: center;">Personal Informaion</h2>

											<div class="itemrow">
									  			<div style="width: 20%; float: left;">
									  				<label>Name: </label>
									  			</div>
									  			<div style="width: 80%; float: left;">
									  				<span>'.$uname_db.'</span>
									  			</div>
								  			</div>
											<div class="itemrow">
									  			<div style="width: 20%; float: left;">
									  				<label>Email: </label>
									  			</div>
									  			<div style="width: 80%; float: left;">
									  				<span>'.$email_db.'</span>
									  			</div>
								  			</div>
											<div class="itemrow">
									  			<div style="width: 20%; float: left;">
									  				<label>Phone: </label>
									  			</div>
									  			<div style="width: 80%; float: left;">
									  				<input type="text" name="phone" value="'.$uphone_db.'"/>
									  			</div>
								  			</div>
											<div class="itemrow">
									  			<div style="width: 20%; float: left;">
									  				<label>Gender: </label>
									  			</div>
									  			<div style="width: 80%; float: left;">
									  				<span>'.ucfirst($ugender_db).'</span>
									  			</div>
								  			</div>
											<div class="itemrow">
									  			<div style="width: 20%; float: left;">
									  				<label>Address: </label>
									  			</div>
									  			<div style="width: 80%; float: left;">
									  				<input type="text" name="address" value="'.$uaddress_db.'"/>
									  			</div>
								  			</div>
											';
											if($utype_db == "student"){

											}else if($utype_db == "teacher"){
												echo '
													<div class="itemrow">
														  <div style="width: 20%; float: left;">
															  <label>Institute: </label>
														  </div>
														  <div style="width: 80%; float: left;">
															  <input type="text" name="inst_nm" value="'.$uinst_db.'"/>
														  </div>
													  </div>';
											}
											 echo'<input style="background:yellow; width:100px; cursor:pointer;" type="submit" class="sub_button" name="updatepic" value="Update"/></br></div><br>
										</div></div>'
								 ?>
							</form>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<script src="./js/script.js"></script>
</body>

</html>