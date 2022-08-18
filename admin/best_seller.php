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
$stat = $pdo->prepare("SELECT * FROM `sale_order_detail` GROUP BY product_id HAVING SUM(quantity) > 5 ORDER BY id DESC");
$stat->execute();
$result = $stat->fetchAll();
?>
<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Best Seller Items</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-hover" id="d-table">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Product</th>
                  <th>Category</th>
                </tr>
              </thead>
              <tbody>
              <?php
                if ($result) {
                  $i = 1;
                  foreach($result as $value){
                    $ustat = $pdo->prepare("SELECT * FROM products WHERE id=:id");
                    $ustat->execute(array(':id'=>$value['product_id']));
                    $uresult = $ustat->fetch(PDO::FETCH_ASSOC);

                    $cstat = $pdo->prepare("SELECT * FROM categories WHERE id=:id");
                    $cstat->execute(array(':id'=>$uresult['category_id']));
                    $cresult = $cstat->fetch(PDO::FETCH_ASSOC);
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo escape($uresult['name']) ?></td>
                        <td><?php echo escape($cresult['name']) ?></td>
                    </tr>
                <?php
                $i++;
                }}
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php require "footer.php"; ?>