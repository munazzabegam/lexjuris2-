<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $order = $_POST['order'];
    
    try {
        $conn->begin_transaction();
        
        foreach ($order as $item) {
            $id = (int)$item['id'];
            $new_order = (int)$item['order'];
            
            $stmt = $conn->prepare("UPDATE articles SET order_index = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_order, $id);
            $stmt->execute();
        }
        
        $conn->commit();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} 