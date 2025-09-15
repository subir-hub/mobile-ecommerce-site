<?php
header("Content-Type: application/json");
session_start();
require '../admin/config/db.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'place_order') {

    $user_id = $_SESSION['user_id'];
    $cartItems = $_SESSION['cart'];

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }

    $taxRate = 0.18;
    $tax = $subtotal * $taxRate;
    $total = $subtotal + $tax;

    $shippingCost = ($subtotal < 500 && count($cartItems) > 0) ? 50 : 0;
    $grandTotal = $shippingCost + $total;

    // Insert into orders table
    $status = 'pending';
    date_default_timezone_set('Asia/Kolkata'); // set timezone to India

    $created_at = date('Y-m-d H:i:s'); // Indian current time

    $sql = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $user_id, $grandTotal, $status, $created_at);

    if (!$stmt->execute()) {
        echo json_encode(['code' => 500, 'message' => 'Failed to place order']);
        exit;
    }
    // Get last inserted order_id
    $order_id = $stmt->insert_id;

    // Insert order items
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_items = $conn->prepare($sql_items);

    foreach ($cartItems as $item) {
        $product_id = $item['productId'];
        $quantity = $item['qty'];
        $price = $item['price'];

        $stmt_items->bind_param("iiid", $order_id, $product_id, $quantity, $price);

        if (!$stmt_items->execute()) {
            echo json_encode(['code' => 500, 'message' => 'Failed to insert order item']);
            exit;
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);

    echo json_encode([
        'code' => 200,
        'message' => 'Order placed successfully!',
        'order_id' => $order_id
    ]);
}
