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
    if (empty($_POST['name'] || empty($_POST['email']))) {
        if (empty($_POST['name'])) {
            $nameError = 'Name cannot be empty!';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email cannot be empty!';
        }
    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
        $passwordError = 'Password should be at least 4 characters!';
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
        $stat->execute(array(":email" => $email, ":id" => $id));
        $user = $stat->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert('Email has already used!')</script>";
        } else {
            if ($_POST['password']) {
                $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $stat = $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
                $result = $stat->execute();
            } else {
                $stat = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
                $result = $stat->execute();
            }
            if ($result) {
                echo "<script>alert('User is updated successfully.');window.location.href='users.php'</script>";
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
                                <p class="text-danger"><?php echo empty($nameError) ?  '' : $nameError; ?></p>
                                <input type="text" name="name" class="form-control" value="<?php echo escape($uresult['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Email</label>
                                <p class="text-danger"><?php echo empty($emailError) ?  '' : $emailError; ?></p>
                                <input type="email" class="form-control" name="email" value="<?php echo escape($uresult['email']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Password</label>
                                <p class="text-danger"><?php echo empty($passwordError) ?  '' : $passwordError; ?></p>
                                <input type="password" class="form-control" name="password">
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