<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order = $data['order'] ?? [];

    if (empty($order)) {
        echo json_encode(['success' => false, 'message' => 'No order data provided.']);
        exit();
    }

    $conn->begin_transaction();
    $success = true;

    $stmt = $conn->prepare("UPDATE achievements SET order_index = ? WHERE id = ?");

    foreach ($order as $index => $achievement_id) {
        $current_order_index = $index + 1; // 1-based index
        $stmt->bind_param("ii", $current_order_index, $achievement_id);
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    if ($success) {
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Order updated successfully.']);
    } else {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update order.', 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
} 