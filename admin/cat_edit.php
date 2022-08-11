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
  if(empty($_POST['name']) || empty($_POST['description'])){
    if(empty($_POST['name'])){
        $nameError = "Category name is required!";
    }
    if(empty($_POST['description'])){
        $descError = "Description is required!";
    }
  }else{
    $name = $_POST['name'];
    $description = $_POST['description'];
    $id = $_POST['id'];
    $stat = $pdo->prepare("UPDATE categories SET name=:name,description=:description WHERE id=:id");
    $result = $stat->execute(
        array(
            ':name' => $name,
            ':description' => $description,
            ':id' => $id
        )
    );
    if($result){
        echo "<script>alert('Category is updated successfully.');window.location.href='categories.php'</script>";
    }
  }
}
$stat = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
$stat->execute();
$result = $stat->fetch(PDO::FETCH_ASSOC);
?>

<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
              <input type="hidden" name="id" value="<?php echo escape($result['id'])?>">
              <div class="form-group">
                <label for="" class="form-label">Name</label>
                <p class="text-danger"><?php echo empty($nameError) ?  '': $nameError; ?></p>
                <input type="text" name="name" class="form-control" value="<?php echo escape($result['name']) ?>">
              </div>
              <div class="form-group">
                <label for="" class="form-label">Description</label>
                <p class="text-danger"><?php echo empty($descError) ?  '': $descError; ?></p>
                <textarea name="description" rows="10" class="form-control"><?php echo escape($result['description']) ?></textarea>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-success" name="" value="SUBMIT" id="">
                <a href="categories.php" class="btn btn-primary">Back</a>
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