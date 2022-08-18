<?php
include "header.php";
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
	header("location:login.php");
}
?>

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($_SESSION['cart'])) {
                            $total = 0;
                            foreach ($_SESSION['cart'] as $key => $qty) :
                                $id = str_replace('id', '', $key);

                                $stat = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                                $stat->execute();
                                $result = $stat->fetch(PDO::FETCH_ASSOC);
                                $total += $result['price'] * $qty;
                        ?>
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="admin/images/<?php echo $result['image'] ?>" alt="" width="100" height="110">
                                            </div>
                                            <div class="media-body">
                                                <p><?php echo escape($result['name']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo escape($result['price']) ?></h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <input type="text" title="Quantity:" value="<?php echo escape($qty) ?>" class="input-text qty" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo escape($result['price'] * $qty) ?></h5>
                                    </td>
                                    <td>
                                        <a class="primary-btn" href="cart_item_clear.php?pid=<?php echo $result['id'] ?>">Clear</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>


                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo escape($total) ?></h5>
                                </td>
                            </tr>

                            <tr class="out_button_area">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="primary-btn" href="clearall.php">Clear All</a>
                                        <a class="gray_btn" href="index.php">Continue Shopping</a>
                                        <a class="primary-btn" href="confirmation.php">Order Submit</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <p class="text-danger">NO ITEMS IN THE CART!</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">
                                    <a class="gray_btn" href="index.php">Back</a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>


    <?php include "footer.php";
