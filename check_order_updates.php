<?php
session_start();
require 'db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check for valid session
if (!isset($_SESSION['id']) && !isset($_SESSION['tracking_order'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['order_ids']) || !is_array($input['order_ids'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

$orderIds = $input['order_ids'];
$lastUpdate = isset($input['last_update']) ? $input['last_update'] : 0;

// Convert timestamp to MySQL datetime format
$lastUpdateDate = date('Y-m-d H:i:s', $lastUpdate / 1000);

$updates = [];

try {
    // Prepare query based on session type
    if (isset($_SESSION['id'])) {
        // For logged-in users
        $userId = $_SESSION['id'];
        $placeholders = str_repeat('?,', count($orderIds) - 1) . '?';
        $sql = "SELECT id, order_status, updated_at FROM orders 
                WHERE user_id = ? AND id IN ($placeholders) 
                AND (updated_at > ? OR updated_at IS NULL)";
        
        $stmt = $conn->prepare($sql);
        $params = array_merge([$userId], $orderIds, [$lastUpdateDate]);
        $types = 'i' . str_repeat('i', count($orderIds)) . 's';
        $stmt->bind_param($types, ...$params);
    } else {
        // For guest users tracking orders
        $trackingData = $_SESSION['tracking_order'];
        $placeholders = str_repeat('?,', count($orderIds) - 1) . '?';
        $sql = "SELECT id, order_status, updated_at FROM orders 
                WHERE tracking_id = ? AND email = ? AND id IN ($placeholders) 
                AND (updated_at > ? OR updated_at IS NULL)";
        
        $stmt = $conn->prepare($sql);
        $params = array_merge([$trackingData['tracking_id'], $trackingData['email']], $orderIds, [$lastUpdateDate]);
        $types = 'ss' . str_repeat('i', count($orderIds)) . 's';
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $updates[] = [
            'order_id' => $row['id'],
            'order_status' => $row['order_status'],
            'updated_at' => $row['updated_at']
        ];
    }
    
    $stmt->close();
    
    // Return the updates
    echo json_encode($updates);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>