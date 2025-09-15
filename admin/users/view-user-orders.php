<?php
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

include_once __DIR__ . '/../config.php';

require '../includes/header.php';
require '../includes/topbar.php';
require '../includes/sidebar.php';
require '../config/db.php';

if (isset($_GET['id'])) {

    $userId = $_GET['id'];

    $user_sql = "SELECT * FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($user_sql);
    $stmt_user->bind_param("i", $userId);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    $user = $user_result->fetch_assoc();

    $sql = "SELECT 
            u.name AS user_name, 
            u.email, 
            o.id AS order_id, 
            o.status AS order_status,
            oi.quantity, 
            p.image, 
            p.product_name, 
            p.price, 
            p.brand
        FROM users u
        JOIN orders o ON u.id = o.user_id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE u.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
}



?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Users</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container text-center py-3">
        <h3 class="mb-4">Orders for <?= $user['name'] ?> (<?= $user['email'] ?>)</h3>
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Product Price</th>
                    <th>Product Brand</th>
                    <th>Order Status</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><img src="../uploads/<?= $row['image'] ?>" width="50"></td>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['brand'] ?></td>
                            <td>
                                <?php
                                $status = $row['order_status'];
                                $badgeClass = 'secondary';

                                if ($status === 'Confirmed') $badgeClass = 'success';
                                elseif ($status === 'Rejected') $badgeClass = 'danger';
                                elseif ($status === 'Cancelled') $badgeClass = 'warning';
                                elseif ($status === 'Pending') $badgeClass = 'info';
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10" class="text-danger">No records found.</td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<?php require '../includes/footer.php' ?>

</body>

</html>