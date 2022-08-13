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
                        <h3 class="card-title">Order Listings</h3>
                    </div>
                    <!-- /.card-header -->
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

                        $stat = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                        $stat->execute();
                        $rawResult = $stat->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrec);

                        $stat = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfrec");
                        $stat->execute();
                        $result = $stat->fetchAll();
                    ?>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Users</th>
                                    <th>Total Price</th>
                                    <th>Order Date</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) { 
                                        $usersmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                                        $usersmt->execute();
                                        $userResult = $usersmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($userResult['name']); ?></td>
                                            <td><?php echo escape($value['total_price']); ?></td>
                                            <td><?php echo escape(date("Y-m-d",strtotime($value['order_date']))); ?></td>
                                            <td class="text-nowrap">
                                                <a href="order_detail.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">View</a>
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