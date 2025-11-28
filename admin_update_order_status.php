<?php
session_start();
require 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle POST requests for status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Validate input
        if (!isset($_POST['order_id']) || !isset($_POST['order_status'])) {
            throw new Exception('Missing required fields');
        }
        
        $orderId = (int)$_POST['order_id'];
        $newStatus = $conn->real_escape_string($_POST['order_status']);
        $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
        $sendNotification = isset($_POST['send_notification']) ? (bool)$_POST['send_notification'] : false;
        
        // Validate status
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed', 'Cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception('Invalid order status');
        }
        
        // Start transaction
        $conn->begin_transaction();
        
        // Get current status for logging
        $stmt = $conn->prepare("SELECT order_status, email, first_name FROM orders WHERE id = ?");
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentOrder = $result->fetch_assoc();
        $stmt->close();
        
        if (!$currentOrder) {
            throw new Exception('Order not found');
        }
        
        // Update order status
        $stmt = $conn->prepare("UPDATE orders SET order_status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('si', $newStatus, $orderId);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            throw new Exception('No changes made to order status');
        }
        $stmt->close();
        
        // Add to status history
        $stmt = $conn->prepare("INSERT INTO order_status_history (order_id, old_status, new_status, changed_by, notes) 
                               VALUES (?, ?, ?, 'admin', ?)");
        $stmt->bind_param('isss', $orderId, $currentOrder['order_status'], $newStatus, $notes);
        $stmt->execute();
        $stmt->close();
        
        // Send email notification if requested
        if ($sendNotification) {
            $to = $currentOrder['email'];
            $subject = "Order #$orderId Status Update";
            $message = "Dear {$currentOrder['first_name']},\n\n";
            $message .= "The status of your order #$orderId has been updated:\n\n";
            $message .= "Old Status: {$currentOrder['order_status']}\n";
            $message .= "New Status: $newStatus\n\n";
            
            if (!empty($notes)) {
                $message .= "Additional Notes:\n$notes\n\n";
            }
            
            $message .= "Thank you for shopping with us!\n";
            $headers = "From: noreply@yourpharmacy.com";
            
            mail($to, $subject, $message, $headers);
        }
        
        // Commit transaction
        $conn->commit();
        
        $response['success'] = true;
        $response['message'] = "Order status updated successfully to $newStatus";
        
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
    }
    
    // Return JSON response for AJAX requests
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
    // For regular form submissions
    if ($response['success']) {
        $_SESSION['success_message'] = $response['message'];
    } else {
        $_SESSION['error_message'] = $response['message'];
    }
    
    header("Location: view_order.php?order_id=$orderId");
    exit();
}

// Handle GET requests (for quick updates from dashboard)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id']) && isset($_GET['status'])) {
    $orderId = (int)$_GET['order_id'];
    $newStatus = $conn->real_escape_string($_GET['status']);
    
    try {
        // Validate status
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed', 'Cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception('Invalid order status');
        }
        
        // Update order status
        $stmt = $conn->prepare("UPDATE orders SET order_status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('si', $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
        
        // Add to status history
        $stmt = $conn->prepare("INSERT INTO order_status_history (order_id, new_status, changed_by) 
                               VALUES (?, ?, 'admin')");
        $stmt->bind_param('is', $orderId, $newStatus);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success_message'] = "Order status updated successfully to $newStatus";
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
    }
    
    header('Location: admin_dashboard.php');
    exit();
}

// If no valid request method, redirect to dashboard
header('Location: admin_dashboard.php');
exit();