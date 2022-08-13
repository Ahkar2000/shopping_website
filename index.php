<?php
include('header.php');
if (isset($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
  }else{
	if(empty($_GET)){
		unset($_COOKIE['search']);
		setcookie('search', null, -1,"/");
	}
  }
if (!empty($_GET)) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$numOfrec = 1;
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
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<?php 
						$catstat = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
						$catstat->execute();
						$catResult = $catstat->fetchAll();
						foreach($catResult as $value){
							$pstat = $pdo->prepare("SELECT * FROM products WHERE category_id=".$value['id']);
							$pstat->execute();
							$presult = $pstat->fetchAll();
					?>
					<li class="main-nav-list">
						<a data-toggle="collapse" href="#">
							<span class="lnr lnr-arrow-right"></span><?php echo escape($value['name']) ?><span class="number"><?php echo count($presult) ?></span>
						</a>
					</li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="d-flex mt-2 bg-white">
					<nav aria-label="Page navigation example">
						<ul class="d-flex">
							<li class="page-item">
								<a href="?pageno=1" class="page-link">First</a>
							</li>
							<li class="page-item <?php if ($pageno <= 1) {
														echo 'disabled';
													}; ?>">
								<a href="<?php if ($pageno <= 1) {
												echo '#';
											} else {
												echo "?pageno=" . ($pageno - 1);
											} ?>" class="page-link"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
							</li>
							<li class="page-item">
								<a href="#" class="page-link active"><?php echo $pageno; ?></a>
							</li>
							<li class="page-item <?php if ($pageno >= $total_pages) {
														echo 'disabled';
													}; ?>">
								<a href="<?php if ($pageno >= $total_pages) {
												echo '#';
											} else {
												echo "?pageno=" . ($pageno + 1);
											} ?>" class="page-link"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
							</li>
							<li class="page-item">
								<a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
			<!-- End Filter Bar -->
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<!-- single product -->
					<?php if($result){
						foreach($result as $key=>$value){
					?>
					<div class="col-lg-4 col-md-6">
						<div class="single-product">
							<img class="img-fluid" src="admin/images/<?php echo $value['image'] ?>" style="height: 250px;">
							<div class="product-details">
								<h6><?php echo escape($value['name']) ?></h6>
								<div class="price">
									<h6><?php echo escape($value['price']) ?> kyats</h6>
								</div>
								<div class="prd-bottom">
									<a href="" class="social-info">
										<span class="ti-bag"></span>
										<p class="hover-text">add to bag</p>
									</a>
									<a href="" class="social-info">
										<span class="lnr lnr-move"></span>
										<p class="hover-text">view more</p>
									</a>
								</div>
							</div>
						</div>
					</div>
					<?php  }}?>
				</div>
			</section>
			<!-- End Best Seller -->
			<?php include('footer.php'); ?>