<?php
session_start();
require "config/config.php";
require "config/common.php";

if($_POST){
	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['phone'])
		|| empty($_POST['password']) || strlen($_POST['password']) <4 || $_POST['cpassword'] != $_POST['password']){
		if(empty($_POST['name'])){
			$nameError = "Name cannot be empty!";
		}	
		if(empty($_POST['email'])){
			$emailError = "Email cannot be empty!";
		}	
		if(empty($_POST['phone'])){
			$phoneError = "Phone cannot be empty!";
		}	
		if(empty($_POST['address'])){
			$addressError = "Address cannot be empty!";
		}
		if(empty($_POST['password'])){
			$passwordError = "Password is required!";
		}
		if(strlen($_POST['password']) <4){
			$passwordError = "Password cannot be less than 4 characters!";
		}	
		if($_POST['cpassword'] != $_POST['password']){
			$cpasswordError = "Passwords do not match!";
		}
	}else{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$password = password_hash($_POST['password'],PASSWORD_DEFAULT);

		$stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");
		$stat->execute(array(':email' => $email));
		$user = $stat -> fetch(PDO::FETCH_ASSOC);
		if($user){
			echo "<script>alert('This email has already existed!')</script>";
		}else{
			$stat = $pdo->prepare("INSERT INTO users (name,email,phone,address,password) VALUES (:name,:email,:phone,:address,:password)");
			$result = $stat->execute(array(':name' => $name,':email' => $email,':phone' => $phone,':address' => $address,':password' => $password));
			if($result){
				echo "<script>alert('Registeration success! You can now login');window.location.href='login.php'</script>";
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>My Shop</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h my-3" href="index.html">My Shop</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="login.php">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="img/login.jpg" alt="">
						<div class="hover">
							<h4>Already have an account?</h4>
							<p>Please Log in here.</p>
							<a class="primary-btn" href="login.php">Log in</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner" style="padding-top: 40px !important;">
						<h3>Register New Account</h3>
						<form class="row login_form" method="post" id="contactForm" novalidate="novalidate">
							<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
							<div class="col-md-12 form-group">
								<input type="text" style="<?php echo empty($nameError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="name" placeholder="Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'">
							</div>
							<div class="col-md-12 form-group">
								<input type="email" style="<?php echo empty($emailError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="col-md-12 form-group">
								<input type="number" style="<?php echo empty($phoneError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" style="<?php echo empty($addressError) ? '':'border: 1px solid red;'?>" id="name" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" style="<?php echo empty($passwordError) ? '':'border: 1px solid red;'?>" id="name" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
								<p class="text-danger mb-0 text-left"><?php echo empty($passwordError) ? '' : $passwordError ?></p>
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" style="<?php echo empty($cpasswordError) ? '':'border: 1px solid red;'?>" id="name" name="cpassword" placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'">
								<p class="text-danger text-left mb-0"><?php echo empty($cpasswordError) ? '' : $cpasswordError ?></p>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" class="primary-btn">Register</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
<div class="container">
<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
  <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved<i class="fa fa-heart-o" aria-hidden="true"></i>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
</div>
</div>
</footer>

	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>