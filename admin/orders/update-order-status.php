<?php 
session_start();
require '../config/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $orderId = intval($_POST['id']);
    $action = $_POST['action'];

    // Map actions to statuses
    $statusMap = [
        'confirm' => 'Confirmed',
        'reject' => 'Rejected',
        'cancel' => 'Cancelled'
    ];

    if(isset($statusMap[$action])) {

        $status = $statusMap[$action];
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);

        if($stmt->execute()) {
            echo json_encode(['code' => 200, 'status' => $status]);
        } else {
            echo json_encode(['code' => 500, 'msg' => 'DB error']);
        }

        $stmt->close();
    } else {
        echo json_encode(['code' => 400, 'msg' => 'Invalid action']);
    }
}
?>