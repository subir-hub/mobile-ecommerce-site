<?php 
include_once __DIR__ . '/config.php';

require './config/db.php';

session_start();

if($_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

require './includes/header.php';
require './includes/topbar.php';
require './includes/sidebar.php';

require './config/db.php';

// Total Users
$userQuery = $conn->query("SELECT COUNT(*) AS total_users FROM users WHERE role = 'user'");
$userCount = $userQuery->fetch_assoc()['total_users'];

// Total Orders
$orderQuery = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$orderCount = $orderQuery->fetch_assoc()['total_orders'];

// New Orders
$newOrderQuery = $conn->query("SELECT COUNT(*) AS new_orders FROM orders WHERE status = 'Pending'");
$newOrders = $newOrderQuery->fetch_assoc()['new_orders'];

// Total Products
$productQuery = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$productCount = $productQuery->fetch_assoc()['total_products'];

// Total Revenue
$revenueQuery = $conn->query("SELECT SUM(total_amount) AS revenue FROM orders WHERE status = 'confirmed'");
$revenue = $revenueQuery->fetch_assoc()['revenue'] ?? 0;

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= $orderCount ?></h3>

                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= $base_url ?>/admin/orders/manage-orders.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $newOrders ?></h3>

                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="<?= $base_url ?>/admin/orders/manage-orders.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $productCount ?></h3>

                            <p>Total Products</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="<?= $base_url ?>/admin/products/view-product.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $userCount ?></h3>

                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= $base_url ?>/admin/users/manage-users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>₹<?= number_format($revenue,2) ?></h3>

                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
</div>

    <?php
    require './includes/footer.php'
    ?>
    </body>
</html>