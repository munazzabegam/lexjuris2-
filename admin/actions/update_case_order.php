<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// Check if order_index column exists, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM cases LIKE 'order_index'");
if ($check_column->num_rows === 0) {
    $conn->query("ALTER TABLE cases ADD COLUMN order_index INT DEFAULT 0");
}

// Get the new order from POST data
$order = $_POST['order'] ?? null;

if (!$order || !is_array($order)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Update each case's order
    foreach ($order as $item) {
        $id = (int)$item['id'];
        $new_order = (int)$item['order'];
        
        $stmt = $conn->prepare("UPDATE cases SET order_index = ? WHERE id = ?");
        $stmt->bind_param('ii', $new_order, $id);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()]);
} 