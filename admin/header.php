<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Site</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li>
          <?php
          $link = $_SERVER['PHP_SELF'];
          $link_array = explode('/', $link);
          $page = end($link_array);
          ?>
          <?php
          $sites = ['index.php', 'categories.php', 'orders.php', 'users.php'];
          if (in_array($page, $sites)) { ?>
            <form method="post" <?php foreach ($sites as $site) {
                                  if ($page == $site) {
                                    echo "action='$site'";
                                  }
                                } ?>>
              <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
              <div class="d-flex">
                <input type="search" name="search" class="form-control mr-2" id="" placeholder="Search">
                <button class="btn btn-default">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </form>
          <?php  }
          ?>

        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Panel</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo $_SESSION['user_name']; ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="index.php" class="nav-link <?php echo $page == 'index.php' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  Products
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="categories.php" class="nav-link <?php echo $page == 'categories.php' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-layer-group"></i>
                <p>
                  Categories
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="users.php" class="nav-link <?php echo $page == 'users.php' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Users
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="orders.php" class="nav-link <?php echo $page == 'orders.php' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Orders
                </p>
              </a>
            </li>
            <?php 
                $dropdownActive = array('weekly_report.php','monthly_report.php','royal_customer.php','best_seller.php');
              ?>
            <li class="nav-item has-treeview menu ">
              <a href="#" class="nav-link <?php if(in_array($page,$dropdownActive)){echo "active";} ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="weekly_report.php" class="nav-link <?php echo $page == 'weekly_report.php' ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Weekly Report</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="monthly_report.php" class="nav-link <?php echo $page == 'monthly_report.php' ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Monthly Report</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="royal_customer.php" class="nav-link <?php echo $page == 'royal_customer.php' ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Royal Customers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="best_seller.php" class="nav-link <?php echo $page == 'best_seller.php' ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Best Seller Items</p>
                  </a>
                </li>
              </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">

      </div>
      <!-- /.content-header -->