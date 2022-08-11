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

            <div class="col-12" id="cats">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Category Listings</h3>
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

                    if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $stat = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                        $stat->execute();
                        $rawResult = $stat->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrec);

                        $stat = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfrec");
                        $stat->execute();
                        $result = $stat->fetchAll();
                    } else {
                        $searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                        $stat = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                        $stat->execute();
                        $rawResult = $stat->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numOfrec);

                        $stat = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrec");
                        $stat->execute();
                        $result = $stat->fetchAll();
                    }

                    ?>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="cat_add.php" type="button" class="btn btn-success">Create New Category</a>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) { ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($value['name']); ?></td>
                                            <td><?php echo escape(substr($value['description'], 0, 50)); ?></td>
                                            <td class="text-nowrap">
                                                <a href="cat_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                                                <a href="cat_delete.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Are you sure to delete?')" type="button" class="btn btn-danger">Delete</a>
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