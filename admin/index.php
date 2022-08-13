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
if (isset($_POST['search'])) {
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
  if (empty($_GET['pageno'])) {
    unset($_COOKIE['search']);
    setcookie('search', null, -1, "/");
  }
}
?>
<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Product Listings</h3>
          </div>
          <?php
          //pagination start
          if ($_GET) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }
          $numOfrec = 5;
          $offset = ($pageno - 1) * $numOfrec;

          //pagination end

          if (empty($_POST['search']) && empty($_COOKIE['search'])) {
            $stat = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
            $stat->execute();
            $rawResult = $stat->fetchAll();
            $total_pages = ceil(count($rawResult) / $numOfrec);

            $stat = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrec");
            $stat->execute();
            $result = $stat->fetchAll();
          } else {
            $searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
            $stat = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
            $stat->execute();
            $rawResult = $stat->fetchAll();
            $total_pages = ceil(count($rawResult) / $numOfrec);

            $stat = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrec");
            $stat->execute();
            $result = $stat->fetchAll();
          }


          ?>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="mb-3">
              <a href="product_add.php" type="button" class="btn btn-success">Create New Product</a>
            </div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>In Stock</th>
                  <th>Price</th>
                  <th style="width: 40px">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result) {
                  $i = 1;
                  foreach ($result as $value) {
                    $catstat = $pdo->prepare("SELECT * FROM categories WHERE id=".$value['category_id']);
                    $catstat->execute();
                    $catresult = $catstat->fetch(PDO::FETCH_ASSOC);
                ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($value['name']); ?></td>
                      <td><?php echo escape(substr($value['description'], 0, 50) . "..."); ?></td>
                      <td><?php echo escape($catresult['name']); ?></td>
                      <td><?php echo escape($value['quantity']); ?></td>
                      <td><?php echo escape($value['price']); ?></td>
                      <td class="text-nowrap">
                        <a href="product_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                        <a href="product_delete.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Are you sure to delete?')" type="button" class="btn btn-danger">Delete</a>
                      </td>
                    </tr>
                <?php
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <nav aria-label="..." class="float-right mt-3">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="?pageno=1">First</a>
                </li>
                <li class="page-item <?php if ($pageno <= 1) {
                                        echo 'disabled';
                                      }; ?>">
                  <a class="page-link" href="<?php if ($pageno <= 1) {
                                                echo '#';
                                              } else {
                                                echo "?pageno=" . ($pageno - 1);
                                              } ?>">Previous</a>
                </li>
                <li class="page-item active" aria-current="page">
                  <a class="page-link" href="#"><?php echo $pageno; ?></a>
                </li>
                <li class="page-item <?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                      }; ?>">
                  <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                echo '#';
                                              } else {
                                                echo "?pageno=" . ($pageno + 1);
                                              } ?>">Next</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>

    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php require "footer.php"; ?>