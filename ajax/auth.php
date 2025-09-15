<?php
header("Content-Type: application/json");
require '../admin/config/db.php';
session_start();

// Signup
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'signup') {
    $signupName = $_REQUEST['signupName'];
    $signupEmail = $_REQUEST['signupEmail'];
    $signupPassword = $_REQUEST['signupPassword'];

    $hashedPassword = password_hash($signupPassword, PASSWORD_BCRYPT);

    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $signupEmail);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(['code' => 409, 'msg' => 'You are already registered']);
    } else {

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $signupName, $signupEmail, $hashedPassword);
        if ($stmt->execute()) {
            echo json_encode(['code' => 200, 'msg' => 'Signup successful! Now Login']);
        } else {
            echo json_encode(['code' => 400, 'msg' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    }

    $checkStmt->close();
}

// Login
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {
    $loginEmail = $_REQUEST['loginEmail'];
    $loginPassword = $_REQUEST['loginPassword'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $loginEmail);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($loginPassword, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            echo json_encode(['code' => 200, 'msg' => 'Login successful... Please wait']);
        } else {
            echo json_encode(['code' => 401, 'msg' => 'Invalid password']);
        }
    } else {
        echo json_encode(['code' => 404, 'msg' => 'User not found']);
    }

    $stmt->close();
}

// Insert user address

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'userAddress') {

    $user_id = $_SESSION['user_id'];
    $fullname = $_REQUEST['fullname'];
    $phone = $_REQUEST['phone'];
    $address1 = $_REQUEST['address1'];
    $city = $_REQUEST['city'];
    $state = $_REQUEST['state'];
    $pincode = $_REQUEST['pincode'];
    $country = $_REQUEST['country'];
    $address_type = $_REQUEST['address_type'];

    $sql = "INSERT INTO user_address (user_id, user_name, mobile_no, address_line1, city, state, pincode, country, address_type) VALUES(?, ?, ?, ?, ?, ?, ? , ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $user_id, $fullname, $phone, $address1, $city, $state, $pincode, $country, $address_type);

    if ($stmt->execute()) {
        echo json_encode(['code' => 200, 'msg' => 'Your address has been saved successfully']);
    } else {
        echo json_encode(['code' => 400, 'msg' => 'Error ' . $stmt->error]);
    }

    $stmt->close();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateUserAddress') {

    $id = $_REQUEST['id'];
    $fullname = $_REQUEST['fullname'];
    $phone = $_REQUEST['phone'];
    $address1 = $_REQUEST['address1'];
    $city = $_REQUEST['city'];
    $state = $_REQUEST['state'];
    $pincode = $_REQUEST['pincode'];
    $country = $_REQUEST['country'];
    $address_type = $_REQUEST['address_type'];

    $updateSql = "UPDATE `user_address` SET `user_name`=?,`mobile_no`=?,`address_line1`=?,`city`=?,`state`=?,`pincode`=?,`country`=?,`address_type`=? WHERE id = ?";

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssssssi", $fullname, $phone, $address1, $city, $state, $pincode, $country, $address_type, $id);

    if($stmt->execute()) {
        echo json_encode(['code' => 200, 'msg' => 'Your address has been updated successfully.']);
    } else {
        echo json_encode(['code' => 400, 'msg' => 'Error ' . $stmt->error]);
    }

    $stmt->close();
}
