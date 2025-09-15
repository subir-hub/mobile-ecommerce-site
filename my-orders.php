<?php
session_start();
require './admin/config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please login first to view your orders.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT 
            o.id AS order_id, 
            o.status AS order_status, 
            o.created_at, 
            oi.quantity, 
            p.product_name, 
            p.price, 
            p.image, 
            p.brand
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require './includes/links.php' ?>
</head>

<body style="padding-top: 70px;">
    <?php require './includes/header.php' ?>

    <div class="container text-center mt-5">
        <h2 class="mb-4">My Orders</h2>

        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-hover bg-white shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php
                                $status = $row['order_status'];
                                $badgeClass = match ($status) {
                                    'Pending' => 'warning',
                                    'Confirmed' => 'success',
                                    'Cancelled' => 'secondary',
                                    'Rejected' => 'danger'
                                };
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                            </td>
                            <td><?= $row['product_name'] ?></td>
                            <td><img src="admin/uploads/<?= $row['image'] ?>" width="50"></td>
                            <td><?= $row['brand'] ?></td>
                            <td>₹<?= $row['price'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>₹<?= $row['price'] * $row['quantity'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info">You haven’t placed any orders yet.</div>
        <?php } ?>
    </div>

    <?php require './includes/footer.php' ?>

</body>

</html>