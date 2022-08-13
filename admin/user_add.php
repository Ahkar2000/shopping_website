<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:login.php");
}
if ($_SESSION['role'] != 1) {
  header("location:login.php");
}
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
  } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
    $passwordError = 'Password should be at least 4 characters!';
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    if (empty($_POST['role'])) {
      $role = 0;
    } else {
      $role = $_POST['role'];
    }

    $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stat->bindValue(':email', $email);
    $stat->execute();
    $user = $stat->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email has already used!')</script>";
    } else {
      $stat = $pdo->prepare("INSERT INTO users(name,email,password,address,phone,role) VALUES (:name,:email,:password,:address,:phone,:role)");
      $result = $stat->execute(
        array(
          ':name' => $name,
          ':email' => $email,
          ':password' => $password,
          ':address' => $address,
          ':phone' => $phone,
          ':role' => $role
        ),
      );
      if ($result) {
        echo "<script>alert('User is added successfully.');window.location.href='users.php'</script>";
      }
    }
  }
}
?>

<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <form action="user_add.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
							<div class="form-group">
                <label for="" class="form-label">Name</label>
								<input type="text" style="<?php echo empty($nameError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="name" placeholder="Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'">
							</div>
							<div class="form-group">
              <label for="" class="form-label">Email</label>
								<input type="email" style="<?php echo empty($emailError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="form-group">
              <label for="" class="form-label">Phone</label>
								<input type="number" style="<?php echo empty($phoneError) ? '':'border: 1px solid red;'?>" class="form-control" id="name" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
							</div>
							<div class="form-group">
              <label for="" class="form-label">Address</label>
								<input type="text" class="form-control" style="<?php echo empty($addressError) ? '':'border: 1px solid red;'?>" id="name" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>
							<div class="form-group">
              <label for="" class="form-label">Password</label>
								<input type="password" class="form-control" style="<?php echo empty($passwordError) ? '':'border: 1px solid red;'?>" id="name" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
								<p class="text-danger mb-0 text-left"><?php echo empty($passwordError) ? '' : $passwordError ?></p>
							</div>
              <div class="form-group">
                <label for="" class="form-label">Confirm Password</label>
								<input type="password" class="form-control" style="<?php echo empty($cpasswordError) ? '':'border: 1px solid red;'?>" id="name" name="cpassword" placeholder="Confirm Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'">
								<p class="text-danger text-left mb-0"><?php echo empty($cpasswordError) ? '' : $cpasswordError ?></p>
							</div>
              <div class="form-group">
                <label for="" class="form-label">Role</label>
                <select name="role" id="" class="form-control">
                  <option disabled selected>Select Role</option>
                  <option value="0">User</option>
                  <option value="1">Admin</option>
                </select>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-success" name="" value="SUBMIT" id="">
                <a href="users.php" class="btn btn-primary">Back</a>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php require "footer.php"; ?>