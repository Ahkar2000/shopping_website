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

            <div class="col-12" id="cats">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Detail</h3>
                    </div>
                    <!-- /.card-header -->
                    <?php
                    //pagination start
                    if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }
                    $numOfrec = 5;
                    $offset = ($pageno - 1) * $numOfrec;

                    //pagination end

                        $stat = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']);
                        $stat->execute();
                        $rawResult = $stat->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrec);

                        $stat = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']." LIMIT $offset,$numOfrec");
                        $stat->execute();
                        $result = $stat->fetchAll();
                    ?>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) { 
                                        $usersmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                                        $usersmt->execute();
                                        $userResult = $usersmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($userResult['name']); ?></td>
                                            <td><?php echo escape($value['quantity']); ?></td>
                                            <td><?php echo escape(date("Y-m-d",strtotime($value['order_date']))); ?></td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <a href="orders.php" class="btn btn-secondary mt-3">Back</a>
                        <nav aria-label="..." class="float-right mt-3">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="?id=<?php echo escape($_GET['id'])?>&pageno=1">First</a>
                                </li>
                                <li class="page-item <?php if ($pageno <= 1) {
                                                            echo 'disabled';
                                                        }; ?>">
                                    <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                    echo '#';
                                                                } else {
                                                                    echo "?id=".$_GET['id']."&pageno=" . ($pageno - 1);
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
                                                                    echo "?id=".$_GET['id']."&pageno=" . ($pageno + 1);
                                                                } ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?id=<?php echo escape($_GET['id'])?>&pageno=<?php echo $total_pages ?>">Last</a>
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