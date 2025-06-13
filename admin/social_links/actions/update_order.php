<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../../config/database.php';

// Check if order_index column exists, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM social_links LIKE 'order_index'");
if ($check_column->num_rows === 0) {
    $conn->query("ALTER TABLE social_links ADD COLUMN order_index INT DEFAULT 0");
}

// Check if the request is POST and contains order data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $order = $_POST['order'];
    
    if (!is_array($order)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid order data']);
        exit();
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // First, reset all order_index values to a high number to avoid conflicts
        $reset_stmt = $conn->prepare("UPDATE social_links SET order_index = 999999");
        $reset_stmt->execute();
        
        // Update each social link's order_index with sequential numbers
        foreach ($order as $index => $item) {
            $social_link_id = (int)$item['id'];
            $new_order = $index + 1; // Start from 1 and increment
            
            $stmt = $conn->prepare("UPDATE social_links SET order_index = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_order, $social_link_id);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['social_link_success'] = "Social link order updated successfully.";
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        $_SESSION['social_link_error'] = "Error updating social link order: " . $e->getMessage();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 