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
$id = $_GET['id'];
$ustat = $pdo->prepare("SELECT * FROM users WHERE id=$id");
$ustat->execute();
$uresult = $ustat->fetch(PDO::FETCH_ASSOC);
if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['phone'])) {
        if (empty($_POST['name'])) {
            $nameError = 'Name cannot be empty!';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email cannot be empty!';
        }
        if(empty($_POST['phone'])){
			$phoneError = "Phone cannot be empty!";
		}	
		if(empty($_POST['address'])){
			$addressError = "Address cannot be empty!";
		}
    }else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        
        if(!empty($_POST['password']) && strlen($_POST['password']) < 4){
            $passwordError = "Passwords should not be less than 4 characters!";
        }else{
            $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
            $stat->execute(array(":email" => $email, ":id" => $id));
            $user = $stat->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                echo "<script>alert('Email has already used!')</script>";
            } else {
                if ($_POST['password']) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stat = $pdo->prepare("UPDATE users SET name='$name',email='$email',address='$address',phone='$phone',password='$password',role='$role' WHERE id='$id'");
                    $result = $stat->execute();
                } else {
                    $stat = $pdo->prepare("UPDATE users SET name='$name',email='$email',address='$address',phone='$phone',role='$role' WHERE id='$id'");
                    $result = $stat->execute();
                }
                if ($result) {
                    echo "<script>alert('User is updated successfully.');window.location.href='users.php'</script>";
                }
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
                        <form action="" method="post">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                            <input type="hidden" name="id" value="<?php echo escape($uresult['id']) ?>">
                            <div class="form-group">
                                <label for="" class="form-label">Name</label>
                                <input type="text" name="name" style="<?php echo empty($nameError) ? '' : 'border: 1px solid red;' ?>" class="form-control" value="<?php echo escape($uresult['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Email</label>
                                <input type="email"  class="form-control" name="email" style="<?php echo empty($emailError) ? '' : 'border: 1px solid red;' ?>"  value="<?php echo escape($uresult['email']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Phone</label>
                                <input type="number" value="<?php echo escape($uresult['phone']) ?>" style="<?php echo empty($phoneError) ? '' : 'border: 1px solid red;' ?>" class="form-control" id="name" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Address</label>
                                <input type="text" class="form-control" value="<?php echo escape($uresult['address']) ?>" style="<?php echo empty($addressError) ? '' : 'border: 1px solid red;' ?>" id="name" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Password</label>
                                <p class="text-danger"><?php echo empty($passwordError) ?  '' : $passwordError; ?></p>
                                <input type="password" class="form-control" name="password" style="<?php echo empty($passwordError) ? '' : 'border: 1px solid red;' ?>" >
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Role</label>
                                <select name="role" class="form-control">
                                    <option disabled selected>Select Role</option>
                                    <option value="0" <?php echo $uresult['role'] == 0 ? 'selected' : '' ?>>User</option>
                                    <option value="1" <?php echo $uresult['role'] == 1 ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" name="" value="SUBMIT">
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