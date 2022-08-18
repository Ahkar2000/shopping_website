<?php
include('header.php');
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
	header("location:login.php");
}
$stat = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
$stat->execute();
$result = $stat->fetch(PDO::FETCH_ASSOC);

$cstat = $pdo->prepare("SELECT * FROM categories WHERE id=" . $result['category_id']);
$cstat->execute();
$cresult = $cstat->fetch(PDO::FETCH_ASSOC);
?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top: 0 !important;">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <div class="">
          <div class="single-prd-item">
            <img class="img-fluid" src="admin/images/<?php echo $result['image'] ?>" alt="" style="height: 500px;">
          </div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?php echo escape($result['name']) ?></h3>
          <h2><?php echo escape($result['price']) ?></h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?php echo escape($cresult['name']) ?></a></li>
            <li><a href="#"><span>Availibility</span> : In Stock</a></li>
          </ul>
          <p><?php echo escape($result['description']) ?></p>
          <form action="addtocart.php" method="post">
            <input type="hidden" name="token" value="<?php $_SESSION['token'] ?>">
            <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <a class="primary-btn" href="index.php">Back</a>
              <button class="primary-btn" style="border:none !important;">Add to Cart</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>



<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include "footer.php"; ?>