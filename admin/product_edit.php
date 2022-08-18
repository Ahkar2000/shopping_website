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
$ustat = $pdo->prepare("SELECT * FROM products WHERE id=$id");
$ustat->execute();
$uresult = $ustat->fetch(PDO::FETCH_ASSOC);
if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])
        || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])
    ) {

        if (empty($_POST['name'])) {
            $nameError = "Category name is required!";
        }
        if (empty($_POST['description'])) {
            $descError = "Description is required!";
        }
        if (empty($_POST['quantity'])) {
            $qtyError = "Quantity is required!";
        } elseif (is_numeric($_POST['quantity']) != 1) {
            $qtyError = "Quantity should be Integer!";
        }
        if (empty($_FILES['image'])) {
            $imageError = "Image is required!";
        }
        if (empty($_POST['price'])) {
            $priceError = "Price is required!";
        } elseif (is_numeric($_POST['price']) != 1) {
            $priceError = "Price should be Integer!";
        }
        if (empty($_POST['category'])) {
            $catError = "Category is required!";
        }
    } else { //validation success
        if (is_numeric($_POST['quantity']) != 1) {
            $qtyError = "Quantity should be Integer!";
        }
        if (is_numeric($_POST['price']) != 1) {
            $priceError = "Price should be Integer!";
        }
        if ($qtyError == '' && $priceError == '') {
            $file = "images/" . $_FILES['image']['name'];
            $imageType = pathinfo($file, PATHINFO_EXTENSION);
            $file = "images/" . $_FILES['image']['name'];
            $imageType = pathinfo($file, PATHINFO_EXTENSION);
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $image = uniqid() . "-" . $_FILES['image']['name'];

            if ($image) {
                if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
                    $imageError = "Image Type is incorrect!";
                    $result = false;
                } else {
                    move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
                    $stat = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category_id,price=:price,quantity=:quantity,image=:image WHERE id=:id");
                    $result = $stat->execute(array(
                        ':name' => $name,
                        ':description' => $description,
                        ':category_id' => $category,
                        ':quantity' => $quantity,
                        ':price' => $price,
                        ':image' => $image,
                        ':id' => $id
                    ));
                }
            } else {
                $stat = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category_id,price=:price,quantity=:quantity WHERE id=:id");
                $result = $stat->execute(array(
                    ':name' => $name,
                    ':description' => $description,
                    ':category_id' => $category,
                    ':quantity' => $quantity,
                    ':price' => $price,
                    ':id' => $id
                ));
            }
            if ($result) {
                echo "<script>alert('Product is updated.');location.href='index.php'</script>";
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

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                            <input type="hidden" name="id" value="<?php echo $uresult['id']; ?>">
                            <div class="form-group">
                                <label for="" class="form-label">Name</label>
                                <p class="text-danger"><?php echo empty($nameError) ?  '' : $nameError; ?></p>
                                <input type="text" name="name" class="form-control" id="" value="<?php echo $uresult['name']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Description</label>
                                <p class="text-danger"><?php echo empty($descError) ?  '' : $descError; ?></p>
                                <textarea name="description" rows="5" class="form-control"><?php echo $uresult['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <?php
                                $catstat = $pdo->prepare("SELECT * FROM categories");
                                $catstat->execute();
                                $catresult = $catstat->fetchAll();
                                ?>
                                <label for="" class="form-label">Category Name</label>
                                <p class="text-danger"><?php echo empty($catError) ?  '' : $catError; ?></p>
                                <select name="category" class="form-control">
                                    <option value="0" disabled selected>Select Category</option>
                                    <?php
                                    foreach ($catresult as $c) {
                                    ?>
                                        <option value="<?php echo $c['id'] ?>" <?php if ($uresult['category_id'] == $c['id']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $c['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Quantity</label>
                                <p class="text-danger"><?php echo empty($qtyError) ?  '' : $qtyError; ?></p>
                                <input type="number" name="quantity" class="form-control" id="" value="<?php echo $uresult['quantity']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Price</label>
                                <p class="text-danger"><?php echo empty($priceError) ?  '' : $priceError; ?></p>
                                <input type="number" name="price" class="form-control" id="" value="<?php echo $uresult['price']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Image</label>
                                <br>
                                <img src="images/<?php echo $uresult['image'] ?>" alt="" width="150">
                                <p class="text-danger"><?php echo empty($imageError) ?  '' : $imageError; ?></p>
                                <input type="file" name="image" class="form-control" id="">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" name="" value="SUBMIT" id="">
                                <a href="index.php" class="btn btn-primary">Back</a>
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