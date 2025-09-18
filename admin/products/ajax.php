<?php
header("Content-Type: application/json");
require '../config/db.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'insertProduct') {

    $productName = $_REQUEST['productName'];
    $model = $_REQUEST['model'];
    $price = $_REQUEST['price'];
    $brand = $_REQUEST['brand'];

    $file = $_FILES['image'];
    $fileName = $file['name'];
    $tmp_name = $file['tmp_name'];
    $error = $file['error'];

    if ($error === 0) {
        $destination = "../uploads/" . $fileName;

        if (move_uploaded_file($tmp_name, $destination)) {
            $sql = "INSERT INTO products (`product_name`, `model`, `price`, `brand`, `image`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $productName, $model, $price, $brand, $fileName);

            if ($stmt->execute()) {
                echo json_encode(['code' => 200, 'msg' => 'Product added successfully!']);
            } else {
                echo json_encode(['code' => 400, 'msg' => 'Error ' . $stmt->error]);
            }
        } else {
            echo json_encode(['code' => 400, 'msg' => 'Failed to move the file']);
        }
    } else {
        echo json_encode(['code' => 400, 'msg' => 'Error while uploading file']);
    }

    $stmt->close();
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateProduct') {

    $id = $_REQUEST['id'];
    $productName = $_REQUEST['productName'];
    $model = $_REQUEST['model'];
    $price = $_REQUEST['price'];
    $brand = $_REQUEST['brand'];

    $fileName = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $tmp_name = $file['tmp_name'];
        $destination = "../uploads/" . $fileName;

        if (!move_uploaded_file($tmp_name, $destination)) {
            echo json_encode(['code' => 400, 'msg' => 'Failed to upload new product image']);
            exit;
        }
    }

    // If new image uploaded, update with image
    if ($fileName) {
        $updateSql = "UPDATE `products` 
                      SET `product_name`=?, `model`=?, `price`=?, `brand`=?, `image`=? 
                      WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssissi", $productName, $model, $price, $brand, $fileName, $id);
    } else {
        // If no new image uploaded, don't update image column
        $updateSql = "UPDATE `products` 
                      SET `product_name`=?, `model`=?, `price`=?, `brand`=? 
                      WHERE id=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssisi", $productName, $model, $price, $brand, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['code' => 200, 'msg' => 'Product updated successfully!']);
    } else {
        echo json_encode(['code' => 500, 'msg' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'delete') {

    $id = $_REQUEST['id'];

    $imgSql = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($imgSql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fetchImg = $result->fetch_assoc();

    if ($fetchImg) {
        $imgFile = $fetchImg['image'];

        $deleteSql = "DELETE FROM products WHERE id = ?";
        $dlt = $conn->prepare($deleteSql);
        $dlt->bind_param("i", $id);

        if ($dlt->execute()) {
            $filePath = "../uploads/" . $imgFile;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            echo json_encode(['code' => 200, 'msg' => 'Product deleted successfully']);
        } else {
            echo json_encode(['code' => 500, 'msg' => 'Error: ' . $dlt->error]);
        }

        $dlt->close();
    } else {
        echo json_encode(['code' => 404, 'msg' => 'Product not found']);
    }

    $stmt->close();
}
