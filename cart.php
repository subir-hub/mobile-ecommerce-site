<?php
session_start();

$cartItems = $_SESSION['cart'] ?? [];

// Calculate Total
$subtotal = 0;

foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

// Final price with tax
$taxRate = 0.18;
$tax = $subtotal * $taxRate; 
$total = $subtotal + $tax;

// Shipping
$shippingCost = (count($cartItems) > 0 && $subtotal < 500) ? 50 : 0;
$totalOrder = $total + $shippingCost;

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <?php require './includes/links.php' ?>

</head>

<body style="padding-top: 70px;">

    <?php require './includes/header.php' ?>
    <div class="container">
        <h3 class="my-3 text-center">Checkout <?= count($cartItems) ?> <?= count($cartItems) > 1 ? 'items' : 'item' ?></h3>
    </div>
    <div class="container my-5">
        <h4 class="mb-3">Review Your Order</h4>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <?php if (!empty($cartItems)) { ?>
                    <?php 
                        foreach ($cartItems as $index => $cartItem) { 
                            $delivery_date = date('l, F j', strtotime($cartItem['added_date'] . ' +3 days'));
                    ?>
                        <div class="card mb-3 shadow-sm order-summary">
                            <div class="row g-0">
                                <div class="col-md-6">
                                    <h4 class="text-success p-2">Delivery by <?= $delivery_date ?></h4>
                                    <img src="admin/uploads/<?= $cartItem['image'] ?>" class="img-fluid rounded-start" width="100">
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $cartItem['product_name'] ?></h5>
                                        <p class="card-text text-muted"><?= $cartItem['model'] ?></p>
                                        <p class="card-text text-danger fw-bold">₹<?= number_format($cartItem['price']) ?></p>


                                        <div class="d-flex align-items-center">
                                            <!-- <p class="me-2 mb-0 fw-semibold">Quantity:</p> -->

                                            <!-- Quantity control -->
                                            <div class="d-flex align-items-center shadow-sm rounded bg-transparent" style="width: fit-content;">

                                                <!-- - button -->
                                                <button class="btn btn-sm text-white fw-bold"
                                                    style="background:green; border:1px solid #1c2a39; width:32px; height:32px;"
                                                    type="button"
                                                    onclick="updateQty(<?= $index ?>, <?= $cartItem['qty'] - 1 ?>)">−</button>

                                                <!-- quantity box -->
                                                <span class="mx-3 fw-semibold"><?= $cartItem['qty'] ?></span>

                                                <!-- + button -->
                                                <button class="btn btn-sm text-white fw-bold"
                                                    style="background:green; border:1px solid #1c2a39; width:32px; height:32px;"
                                                    type="button"
                                                    onclick="updateQty(<?= $index ?>, <?= $cartItem['qty'] + 1 ?>)">+</button>
                                            </div>


                                            <!-- Remove button -->
                                            <form action="ajax/ajax.php" method="POST" onsubmit="return confirmDelete();" class="ms-5">
                                                <input type="hidden" name="index" value="<?= $index; ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-warning">Your cart is empty.</div>
                    <a href="./index.php" class="btn btn-warning my-3">View products</a>
                <?php } ?>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h5>Order Summary</h5>
                    <hr>
                    <p>Items (<?= count($cartItems) ?>): <span class="float-end">₹<?= number_format($subtotal, 2) ?></span></p>
                    <p>Shipping & handling: 
                        <span class="float-end"><?= $shippingCost > 0 ? "₹" . number_format($shippingCost, 2) : "<span class='text-success'>FREE</span>" ?></span>
                    </p>
                    <p>Total before tax: <span class="float-end">₹<?= number_format($subtotal, 2) ?></span></p>
                    <p>Estimated tax (<?= $taxRate * 100 ?>%): <span class="float-end">₹<?= number_format($tax, 2) ?></span></p>
                    <hr>
                    <h5>Order total: <span class="float-end text-danger">₹<?= number_format($totalOrder, 2) ?></span></h5>
                    <a href="checkout.php" class="btn btn-warning w-100 mt-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <?php  require './includes/footer.php' ?>

    <script>
        function updateQty(index, newQty) {
            if (newQty < 1) {
                alert('Quantity cannot be less than 1');
                return;
            }

            $.ajax({
                type: "POST",
                url: "ajax/ajax.php",
                data: {
                    action: 'updateQuantity',
                    index: index,
                    qty: newQty
                },
                dataType: "json",
                success: function(response) {
                    if (response.code === 200) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this car?");
        }
    </script>
</body>

</html>