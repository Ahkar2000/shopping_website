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
?>
<?php require "header.php"; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Weekly Reports</h3>
          </div>
          <?php
            $current = date("Y-m-d");
            $from_date = date("Y-m-d",strtotime($current.'+1day'));
            $to_date = date("Y-m-d",strtotime($current.'-7day'));

            $stat = $pdo->prepare("SELECT * FROM `sale_orders` WHERE order_date<:from_date AND order_date >=:to_date ORDER BY id DESC");
            $stat->execute(array(':from_date'=>$from_date,':to_date'=>$to_date));
            $result = $stat->fetchAll();
          ?>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-hover" id="d-table">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>User</th>
                  <th>Total Amount</th>
                  <th>Order Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result) {
                  $i = 1;
                  foreach($result as $value){
                    $ustat = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                    $ustat->execute(array(':id'=>$value['user_id']));
                    $uresult = $ustat->fetch(PDO::FETCH_ASSOC);
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo escape($uresult['name']) ?></td>
                        <td><?php echo escape($value['total_price']) ?></td>
                        <td><?php echo escape(date("Y-m-d",strtotime($value['order_date']))) ?></td>
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
