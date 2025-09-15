<?php 
session_start();
header("Content-Type: application/json");
require './config/db.php';

if(isset($_REQUEST['action']) && $_REQUEST['action'] === 'login') {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])) {

            $_SESSION['adminEmail'] = $user['email'];
            $_SESSION['loggedIn'] = true;
            
            echo json_encode(["code" => 200, 'msg' => 'Login successful']);
        } else {
            echo json_encode(["code" => 400, 'msg' => 'Invalid password']);
        }
    } else {
            echo json_encode(["code" => 404, 'msg' => 'Admin not found']);
    }
}
?>