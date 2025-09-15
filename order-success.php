<?php
session_start();

require './admin/config/db.php';

if (!isset($_SESSION['user_email'])) {
    echo "<script>
            alert('Please login first to continue.');
            window.location.href = 'login.php';
          </script>";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <?php require './includes/links.php' ?>
</head>

<body style="padding-top: 70px;">

    <?php require './includes/header.php' ?>

    <div class="container my-5">

        <div class="text-center py-5">
            <i class="fas fa-check-circle text-success mb-4" style="font-size: 80px;"></i>
            <h1 class="text-success mb-3" style="font-weight: 700;">Thank You, <?= $_SESSION['user_name'] ?>!</h1>
            <p class="lead mb-4">Your order has been placed <span class="text-primary">successfully</span>! 🎉</p>
            <p class="text-muted">We’ll get in touch with you shortly with all the order details.</p>
            <a href="index.php" class="btn btn-primary mt-4 px-4 py-2 rounded-pill shadow-sm">Continue Shopping</a>
        </div>

    </div>

    <?php require './includes/footer.php' ?>


</body>

</html>