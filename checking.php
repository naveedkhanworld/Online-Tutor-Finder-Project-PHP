<?php
 include ( "inc/connection.inc.php" );

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: logout.php');
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_name = $result->fetch_assoc();
			$uname_db = $get_user_name['fullname'];
			$utype_db = $get_user_name['type'];
}

if (isset($_REQUEST['teacher'])) {
	if($_REQUEST['teacher'] == "logastchr"){
		$error = "You Logged as Teacher. Only Student can post!";
	}else{
		header('location: logout.php');
	}
}else{
		header('location: logout.php');
	}

?>



<!DOCTYPE html>
<html>
<head>
	<title>Checking</title>	
</head>
<body class="body1" style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
<div>
	<div>
		<div class="topnav">
			<a  onclick="w3_open()"><img src="image/menuicon2.png" width="16px" height="15px"></a>
			<a class="active" href="index.php" style="margin: 0px 0px 0px 52px;">Students</a>
			<a href="search.php">Search Tutor</a>
			<?php 
			if($utype_db == "teacher")
				{

				}else {
					echo '<a href="postform.php">Post</a>';
				}

			 ?>
			<a href="#contact">Contact</a>
			<a href="#about">About</a>
			<div style="float: right;" >
				<table>
					<tr>
						<?php
							if($user != ""){
								echo '<td>
							<a href="profile.php?uid='.$user.'">'.$uname_db.'</a>
						</td>
						<td>
							<a href="logout.php">Logout</a>
						</td>';
							}else{
								echo '<td>
							<a href="login.php">Login</a>
						</td>
						<td>
							<a href="registration.php">Register</a>
						</td>';
							}
						?>
						
					</tr>
				</table>
			</div>
		</div>
	</div>

	<!-- Students -->
	<div class="nbody" style="margin: 0px 100px; overflow: hidden;">
		<div class="nfeedleft">

			<?php
					echo '
						<div class="nfbody">
					<div class="p_body">
						<p>'.$error.'</p>
					</div>
				</div>';

			?>
		</div>
	</div>

</div>
<script>
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}
</script>
</body>
<script src="./js/script.js"></script>
</html>