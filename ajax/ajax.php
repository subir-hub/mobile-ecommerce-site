<?php
session_start();
// session_destroy();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addToCart') {
    $productId = $_REQUEST['productId'];
    $product_name = $_REQUEST['product_name'] ?? '';
    $model = $_REQUEST['model'] ?? '';
    $price = (int)($_REQUEST['price'] ?? 0);
    $image = $_REQUEST['image'] ?? '';
    $qty = (int)($_REQUEST['qty'] ?? 1);

      // Add current date for delivery calculation
    $added_date = date('Y-m-d'); 


    $item = ['productId' => $productId, 'image' => $image, 'product_name' => $product_name, 'model' => $model, 'price' => $price, 'image' => $image, 'qty' => $qty, 'added_date' => $added_date];

    if(!$item['product_name']) {
        echo json_encode(['code' => 400, 'msg' => 'Invalid input']);
        exit;
    }

    // Create cart if not exists
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $alreadyInCart = false;

    // Check if this product is already in cart
    foreach($_SESSION['cart'] as $cartItem) {
        if($cartItem['productId'] === $item['productId']) {
            $alreadyInCart = true;
            break;
        }
    }

    if($alreadyInCart) {
        echo json_encode(['code' => 409, 'msg' => 'Product already in cart']);
    } else {
        $_SESSION['cart'][] = $item; // Add only if new

        echo json_encode(['code' => 200, 'msg' => 'Product added to cart', 'count' => count($_SESSION['cart'])]);
    }

    exit;
}

// Update Qty
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateQuantity') {
    $index = $_REQUEST['index'];
    $qty = (int)($_REQUEST['qty']);

    if(isset($_SESSION['cart'][$index])) {
        if($qty > 0) {
            $_SESSION['cart'][$index]['qty'] = $qty;
            echo json_encode(['code' => 200, 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['code' => 400, 'message' => 'Quantity must be at least 1']);
        }
    } else {
        echo json_encode(['code' => 404, 'message' => 'Item not found']);
    }
    exit;
}

// Delete item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['index'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['cart'][$index])) {

        unset($_SESSION['cart'][$index]);
    }
    header("Location: ../cart.php");
    exit;
}
