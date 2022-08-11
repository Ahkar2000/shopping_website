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
if ($_POST) {
  if (empty($_POST['name'] || empty($_POST['email'])) || empty($_POST['password'])) {
    if (empty($_POST['name'])) {
      $nameError = 'Name cannot be empty!';
    }
    if (empty($_POST['email'])) {
      $emailError = 'Email cannot be empty!';
    }
    if (empty($_POST['password'])) {
      $passwordError = 'Password cannot be empty!';
    }
  } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
    $passwordError = 'Password should be at least 4 characters!';
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
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
      $stat = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role)");
      $result = $stat->execute(
        array(
          ':name' => $name,
          ':email' => $email,
          ':password' => $password,
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
              <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
              <div class="form-group">
                <label for="" class="form-label">Name</label>
                <p class="text-danger"><?php echo empty($nameError) ?  '' : $nameError; ?></p>
                <input type="text" name="name" class="form-control" id="">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Email</label>
                <p class="text-danger"><?php echo empty($emailError) ?  '' : $emailError; ?></p>
                <input type="email" class="form-control" name="email" id="">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Password</label>
                <p class="text-danger"><?php echo empty($passwordError) ?  '' : $passwordError; ?></p>
                <input type="password" class="form-control" name="password" id="">
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