<?php
session_start();
require './admin/config/db.php';

// Check if user logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>
        alert('Please login first to continue.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Check if cart has items
if (empty($_SESSION['cart'])) {
    echo "<script>
        alert('Your cart is empty');
        window.location.href = 'index.php';
    </script>";
    exit;
}

$cartItems = $_SESSION['cart'] ?? [];

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

$taxRate = 0.18;
$tax = $subtotal * $taxRate;
$total = $subtotal + $tax;
$shippingCost = ($subtotal < 500 && count($cartItems) > 0) ? 50 : 0;
$totalOrder = $total + $shippingCost;

// Fetch user address
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user_address WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userAddress = $result->fetch_assoc();
$stmt->close();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="./assets/css/checkout.css">
    <?php require './includes/links.php'; ?>
</head>

<body style="padding-top: 70px;">
    <?php require './includes/header.php'; ?>

    <div class="container checkout-wrapper">
        <div class="row g-4">

            <!-- LEFT SIDE (Address + Cart stacked) -->
            <div class="col-lg-8">

                <!-- Delivery Address -->
                <div class="section-box mb-4">
                    <div class="section-header bg-primary text-white">
                        <i class="bi bi-geo-alt"></i> Delivery Address
                    </div>
                    <div class="address-details">
                        <?php 
                           if ($userAddress) { 
                            $_SESSION['userAddressId'] = $userAddress['id'];
                        ?>
                            <p><strong><?= $userAddress['user_name'] ?></strong></p>
                            <p><?= $userAddress['address_line1'] ?>,
                                <?= $userAddress['city'] ?>,
                                <?= $userAddress['state'] ?> -
                                <?= $userAddress['pincode'] ?>
                            </p>
                            <p>📞 <?= $userAddress['mobile_no'] ?></p>
                            <a href="profile.php?id=<?= $userAddress['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">Change Address</a>
                        <?php } else { ?>
                            <p class="text-muted">No address saved yet.</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal">
                                + Add New Address
                            </button>
                        <?php } ?>
                    </div>
                </div>


                <!-- Cart Items -->
                <div class="section-box">
                    <div class="section-header bg-info text-white d-flex align-items-center">
                        <i class="bi bi-cart-check me-2"></i> Your Cart
                    </div>

                    <?php if (!empty($cartItems)) {
                        foreach ($cartItems as $cartItem) {
                            $delivery_date = date('l, F j', strtotime($cartItem['added_date'] . ' +3 days'));
                    ?>
                            <div class="container mt-3">

                                <div class="row">
                                    <div class="col-md-6 mb-3">

                                        <!-- Left: Product details -->
                                        <div class="d-flex align-items-center">
                                            <img src="admin/uploads/<?= $cartItem['image'] ?>"
                                                alt="<?= $cartItem['product_name'] ?>"
                                                class="me-3 rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1"><?= $cartItem['product_name'] ?></h6>
                                                <small class="text-muted"><?= $cartItem['model'] ?></small><br>
                                                <span class="text-danger fw-bold">₹<?= number_format($cartItem['price']) ?></span> × <?= $cartItem['qty'] ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <!-- Right: Delivery date -->
                                        <div class="text-end ms-5">
                                            <p class="mb-1 text-success fw-semibold">
                                                Delivery by <span class="fw-bold"><?= $delivery_date ?></span>
                                            </p>
                                            <small class="text-muted">Free Delivery</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="p-3">Your cart is empty. <a href="index.php">Shop now</a></div>
                    <?php } ?>
                </div>


            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">
                <div class="section-box position-sticky" style="top: 90px;">
                    <div class="section-header bg-warning text-dark">
                        <i class="bi bi-receipt"></i> Order Summary
                    </div>
                    <div class="order-summary-box">
                        <div class="summary-line"><span>Items (<?= count($cartItems) ?>)</span><span>₹<?= number_format($subtotal, 2) ?></span></div>
                        <div class="summary-line"><span>Shipping</span><span><?= $shippingCost > 0 ? "₹" . number_format($shippingCost, 2) : "<span class='text-success'>FREE</span>" ?></span></div>
                        <div class="summary-line"><span>Tax (<?= $taxRate * 100 ?>%)</span><span>₹<?= number_format($tax, 2) ?></span></div>
                        <hr>
                        <div class="summary-line"><strong>Order Total</strong><span class="order-total">₹<?= number_format($totalOrder, 2) ?></span></div>

                        <?php if ($userAddress) { ?>
                            <hr>
                            <div class="mb-3 text-start">
                                <label class="fw-semibold fs-6 mb-2">Payment Method</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                    <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                                </div>
                            </div>
                            <form id="placeOrder">
                                <button id="placeOrderBtn" class="btn btn-checkout w-100">Place Your Order</button>
                                <div id="responseMsg" class="text-center fw-semibold mt-2"></div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="userAddress">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Delivery Address</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="fullname" value="<?= $_SESSION['user_name'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" name="phone" maxlength="10" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address Line</label>
                            <input type="text" class="form-control" name="address1" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">City</label><input type="text" class="form-control" name="city" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">State</label><input type="text" class="form-control" name="state" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Pincode</label><input type="text" class="form-control" name="pincode" maxlength="6" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Country</label><input type="text" class="form-control" name="country" value="India" required></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address Type</label><br>
                            <input type="radio" name="address_type" value="Home" checked> Home
                            <input type="radio" name="address_type" value="Work"> Work
                        </div>
                        <div class="d-grid"><button type="submit" class="btn btn-primary">Save & Continue</button></div>
                        <p id="result" class="text-center fw-semibold mt-2"></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require './includes/footer.php' ?>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Save user address
            $("#userAddress").on("submit", function(e) {
                e.preventDefault();
                let data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "ajax/auth.php",
                    data: data + '&action=userAddress',
                    dataType: "json",
                    success: function(response) {
                        if (response.code === 200) {
                            $("#result").text(response.msg).css("color", "green");
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            $("#result").text(response.msg).css("color", "red");
                        }
                    }
                });
            });

            // Place order
            $("#placeOrderBtn").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "ajax/place-order.php",
                    data: {
                        place_order: true,
                        action: 'place_order'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.code === 200) {
                            $("#responseMsg").text(response.message).css("color", "green");
                            setTimeout(() => window.location.href = "./order-success.php", 2000);
                        } else {
                            $("#responseMsg").text(response.message).css("color", "red");
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>