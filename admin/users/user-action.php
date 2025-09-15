<?php 
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userId = intval($_POST['id']);
    $action = $_POST['action'] ?? '';

    if($action === 'delete') {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            echo json_encode(['code' => 200, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['code' => 400, 'message' => 'Failed to delete user']);
        }

    } elseif($action === 'block') {

        $sql = "UPDATE users SET status = 'Blocked' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            echo json_encode(['code' => 200, 'message' => 'User blocked successfully']);
        } else {
            echo json_encode(['code' => 400, 'message' => 'Failed to block user']);
        }

    } elseif ($action === "unblock") {
        $sql = "UPDATE users SET status = 'Active' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "User unblocked successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to unblock user"]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "Unknown action"]);
    }
}
?>