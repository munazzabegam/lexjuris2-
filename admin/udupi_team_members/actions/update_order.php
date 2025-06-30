<?php
session_start();
require_once '../../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $order_data = $_POST['order'];
    
    if (!is_array($order_data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid order data']);
        exit();
    }

    try {
        $conn->begin_transaction();
        
        foreach ($order_data as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                $id = (int)$item['id'];
                $order = (int)$item['order'];
                
                $query = "UPDATE udupi_team_members SET order_index = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $order, $id);
                $stmt->execute();
            }
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
        
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 