<?php
 include ( "inc/connection.inc.php" );

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: login.php');
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_name = $result->fetch_assoc();
			$uname_db = $get_user_name['fullname'];
			$utype_db = $get_user_name['type'];
}

if (isset($_REQUEST['confirm'])) {
		$tid = mysqli_real_escape_string($con, $_REQUEST['confirm']);
		$result = mysqli_query($con, "INSERT INTO applied_post (applied_by,applied_to) VALUES ('$user','$tid')");

		$result = $con->query("SELECT * FROM user WHERE id='$tid'");
		$get_user_name = $result->fetch_assoc();
			$uname = $get_user_name['fullname'];
			$utype = $get_user_name['type'];
		$error = "You Successfully Selected <a href='aboutme.php?uid=".$tid."' style='color: #4CAF50;'>".$uname."</a>";
}else{
		header('location: logout.php');
	}

?>



<!DOCTYPE html>
<html>
<head>
	<title>Confirm</title>
</head>
<body class="body1" style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
	</div>
		<div class="message">
			<?php
					echo '
						<center><div class="nfbody">
					<div class="p_body">
						<p>'.$error.'</p></center>
					</div>
				</div>';
			?>
		</div>
</div>

</body>
</html>