<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tutor Finder</title>
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
	<link rel="stylesheet" type="text/css" href="css/nfeed.css">
</head>
<body style="  background:fixed url(./image/bg1.jpg);background-size: 100%;">
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
<div class="container">
        <div class="nbody">
            <div class="nfeedleft" >
                    <?php
                        $todaydate = date("m/d/Y"); //Month/Day/Year 15/05/2023
    
                        $query = $con->query("SELECT * FROM post ORDER BY id DESC");
                        while ($row = $query->fetch_assoc()) {
                            $post_id = $row['id'];
                            $postby_id = $row['postby_id'];
                            $sub = $row['subject'];
                            $sub = str_replace(",", ", ", $sub);
                            $class = $row['class'];
                            $class = str_replace(",", ", ", $class);
                            $salary = $row['salary'];
                            $location = $row['location'];
                            $location = str_replace(",", ", ", $location);
                            $p_university = $row['p_university'];
                            $post_time = $row['post_time'];
                            $deadline = $row['deadline'];
                            $medium = $row['medium'];
                            $medium = str_replace(",", ", ", $medium);
    
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
    
                            echo '<div class="nfbody">
                            <center><div class="st_prof">';
                                    if($user!='' && $utype_db=='student'){
                                    }else {
                                        if((strtotime($deadline) - strtotime($todaydate)) < 0){
                                            echo '
                                            <input type="submit" style="background:red; color:white; margin-buttom:5px;" class="sub_button" name="" value="Deadline Over" />';
                                        }else{
                                            $resultpostcheck = $con->query("SELECT * FROM applied_post WHERE post_id='$post_id' AND applied_by='$user'");
                                                $query_apply_cnt = $resultpostcheck->num_rows;
                                                if($query_apply_cnt > 0){
                                                    echo '
                                                <input type="submit" style="background:yellow; color:gray; margin-buttom:5px;" class="sub_button" name=""value="Already Applied" />';
                                                }else{
                                                echo '<form action="viewpost.php?pid='.$post_id.'" method="post">
                                        <input type="submit" class="sub_button" name="" value="Apply" style="background: rgba(0, 128, 0, 0.445); color:white; cursor:pointer; margin-buttom:5px;" />';}
                                        }
                                    }
                                echo '<div class="prof-pic">
                                <br><img src="image/profilepic/'.$pro_pic_db.'" width="10px" height="10px">
                                </div><br>
                                <div class="p_nmdate">
                                    <h4>'.$uname_db.'</h4><br>
                                    <h5><a class="c_ptime" href="viewpost.php?pid='.$post_id.'">'.$time->time_ago($post_time).'</a> &nbsp;|&nbsp; Deadline: '.$deadline.'</h5><br>
                                </div>
                            </div><br>
                            <table class="table-data">
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
                        </div></center>';
                        }
                    ?>
            </div>
        </div>
    </div>
<div class="footer">
    <footer>
        <center>
            <h2>Developed by Naveed & Asim</h2><br>
            <a href="./contact.php">Contact Us</a>
        </center>
    </footer>
</div>
    </div>
<script src="./js/script.js"></script>
</body>
</html>
