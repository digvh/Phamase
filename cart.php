 
<?php
session_start();
require 'db.php';

// Check if user is logged in as customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: userlogin.php');
    exit();
}

// Get the logged-in user ID
$userId = $_SESSION['user_id'];

function generateTrackingId() {
    return 'TRK-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

function sendSMSNotification($phone, $message) {
    // Replace with your SMS gateway API credentials and endpoint
    $apiKey = 'YOUR_SMS_API_KEY';
    $apiUrl = 'https://api.smsgateway.com/send';
    
    $data = array(
        'api_key' => $apiKey,
        'to' => $phone,
        'message' => $message
    );
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    
    return $result !== FALSE;
}

function sendWhatsAppNotification($phone, $message) {
    // Using WhatsApp Business API or third-party service like Twilio
    $apiKey = 'YOUR_WHATSAPP_API_KEY';
    $apiUrl = 'https://api.whatsapp.com/send';
    
    // Clean phone number (remove spaces, dashes, etc.)
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    $data = array(
        'phone' => $cleanPhone,
        'text' => $message,
        'apikey' => $apiKey
    );
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    
    return $result !== FALSE;
}

// Add to cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $productId = (int) $_GET['id'];

    // Fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        if (!isset($_SESSION['cart'][$productId])) {
            // Add new product to the cart
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
            
            // Save to database
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param('ii', $userId, $productId);
            $stmt->execute();
        } else {
            // Increment the quantity if the product is already in the cart
            $_SESSION['cart'][$productId]['quantity'] += 1;

            // Update the quantity in the cart table
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param('ii', $userId, $productId);
            $stmt->execute();
        }
    }

    $stmt->close();
    header('Location: cart.php');
    exit;
}

// Update cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && isset($_GET['quantity'])) {
    $productId = (int)$_GET['id'];
    $newQuantity = (int)$_GET['quantity'];
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        
        // Update database
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('iii', $newQuantity, $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: cart.php');
    exit;
}

// Remove from cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $productId = (int) $_GET['id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Remove from database
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: cart.php');
    exit;
}

// Fetch products from the database
$cartProducts = [];
if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    if (count($productIds) > 0) {
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($productIds)), ...$productIds);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $cartProducts[] = $row;
        }
        $stmt->close();
    }
}

$locations = [
    'Arusha' => 'Arusha',
    'Dar es Salaam' => 'Dar es Salaam',
    'Dodoma' => 'Dodoma',
    'Geita' => 'Geita',
    'Iringa' => 'Iringa',
    'Kagera' => 'Kagera',
    'Kigoma' => 'Kigoma',
    'Kilimanjaro' => 'Kilimanjaro',
    'Lindi' => 'Lindi',
    'Manyara' => 'Manyara',
    'Mara' => 'Mara',
    'Mbeya' => 'Mbeya',
    'Morogoro' => 'Morogoro',
    'Mtwara' => 'Mtwara',
    'Mwanza' => 'Mwanza',
    'Njombe' => 'Njombe',
    'Pemba North' => 'Pemba North',
    'Pemba South' => 'Pemba South',
    'Pwani' => 'Pwani',
    'Rukwa' => 'Rukwa',
    'Ruvuma' => 'Ruvuma',
    'Shinyanga' => 'Shinyanga',
    'Simiyu' => 'Simiyu',
    'Singida' => 'Singida',
    'Tabora' => 'Tabora',
    'Tanga' => 'Tanga',
    'Zanzibar Central/South' => 'Zanzibar Central/South',
    'Zanzibar North' => 'Zanzibar North',
    'Zanzibar Urban/West' => 'Zanzibar Urban/West'
];

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $notificationMethod = filter_input(INPUT_POST, 'notification_method', FILTER_SANITIZE_STRING);
    
    // Generate tracking ID
    $trackingId = generateTrackingId();
    
    // Insert order into the orders table
    $orderStatus = 'Pending';
    
    try {
        $stmt = $conn->prepare("INSERT INTO orders (tracking_id, first_name, last_name, email, city, state, phone, order_status, notification_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $trackingId, $firstName, $lastName, $email, $city, $state, $phone, $orderStatus, $notificationMethod);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        
        // Insert order items
        foreach ($_SESSION['cart'] as $productId => $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiid', $orderId, $productId, $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();
        }

        // Store tracking information in session
        $_SESSION['tracking_order'] = [
            'id' => $orderId,
            'tracking_id' => $trackingId,
            'email' => $email
        ];

        $orderTotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $orderTotal += $item['price'] * $item['quantity'];
        }

        // Send Email Notification
        $emailSent = false;
        if ($notificationMethod == 'email' || $notificationMethod == 'both') {
            $to = $email;
            $subject = "Order Confirmation #$orderId - Vasco Pharmacy";
            $message = "
            <html>
            <head>
                <title>Order Confirmation</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .tracking-id { background: #f0f8ff; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0; }
                    .footer { background: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h2>Order Confirmation</h2>
                </div>
                <div class='content'>
                    <p>Dear $firstName $lastName,</p>
                    <p>Thank you for your order! Your order has been received and is being processed.</p>
                    
                    <div class='tracking-id'>
                        <strong>Tracking ID:</strong> $trackingId<br>
                        <strong>Order Total:</strong> $" . number_format($orderTotal, 2) . "<br>
                        <strong>Order Date:</strong> " . date('F j, Y g:i A') . "
                    </div>
                    
                    <h3>Delivery Information</h3>
                    <p>$firstName $lastName<br>$city, $state<br>Phone: $phone</p>
                    
                    <p><strong>Expected Delivery:</strong> 3-5 business days</p>
                    <p>You can track your order status using your tracking ID on our website.</p>
                </div>
                <div class='footer'>
                    <p>© 2023 Vasco Pharmaceutical Company Limited. All rights reserved.</p>
                </div>
            </body>
            </html>
            ";
            
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: no-reply@vascopharmacy.com\r\n";
            $headers .= "Reply-To: support@vascopharmacy.com\r\n";
            
            $emailSent = mail($to, $subject, $message, $headers);
        }

        // Send SMS Notification
        $smsSent = false;
        if ($notificationMethod == 'sms' || $notificationMethod == 'both') {
            $smsMessage = "Vasco Pharmacy: Your order #$orderId has been confirmed. Tracking ID: $trackingId. Total: $" . number_format($orderTotal, 2) . ". Expected delivery: 3-5 days. Track at: vascopharmacy.com/track";
            $smsSent = sendSMSNotification($phone, $smsMessage);
        }

        // Send WhatsApp Notification
        $whatsappSent = false;
        if ($notificationMethod == 'whatsapp' || $notificationMethod == 'both') {
            $whatsappMessage = "🏥 *Vasco Pharmacy - Order Confirmation*\n\n";
            $whatsappMessage .= "Dear $firstName,\n\n";
            $whatsappMessage .= "✅ Your order has been confirmed!\n";
            $whatsappMessage .= "📋 Order ID: #$orderId\n";
            $whatsappMessage .= "🔍 Tracking ID: *$trackingId*\n";
            $whatsappMessage .= "💰 Total: $" . number_format($orderTotal, 2) . "\n";
            $whatsappMessage .= "📅 Order Date: " . date('F j, Y g:i A') . "\n";
            $whatsappMessage .= "🚚 Expected Delivery: 3-5 business days\n\n";
            $whatsappMessage .= "Track your order: vascopharmacy.com/track\n\n";
            $whatsappMessage .= "Thank you for choosing Vasco Pharmacy! 💊";
            
            $whatsappSent = sendWhatsAppNotification($phone, $whatsappMessage);
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Set success message
        $_SESSION['order_success'] = [
            'order_id' => $orderId,
            'tracking_id' => $trackingId,
            'email_sent' => $emailSent,
            'sms_sent' => $smsSent,
            'whatsapp_sent' => $whatsappSent,
            'notification_method' => $notificationMethod
        ];

        // Redirect to orders page
        header('Location: my_orders.php?order_id=' . $orderId);
        exit;

    } catch (Exception $e) {
        $error_message = "Error processing your order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Rest of your HTML remains exactly the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Online Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       :root {
    --primary-color: #4CAF50;
    --secondary-color: #45a049;
    --accent-color: #ff5722;
    --light-gray: #f8f9fa;
    --medium-gray: #e9ecef;
    --dark-gray: #6c757d;
    --white: #ffffff;
    --black: #212529;
    --border-radius: 12px;
    --box-shadow-light: 0 4px 6px rgba(0, 0, 0, 0.07);
    --box-shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.15);
    --box-shadow-heavy: 0 15px 35px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* Enhanced body styles */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: var(--black);
    line-height: 1.6;
    overflow-x: hidden;
    position: relative;
    min-height: 100vh;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
    pointer-events: none;
    z-index: -1;
}

/* Enhanced header styles */
header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--box-shadow-medium);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-container img {
    height: 50px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-light);
    transition: var(--transition);
}

.logo-container img:hover {
    transform: scale(1.05);
}

.logo-text {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 800;
    font-size: 1.8rem;
    letter-spacing: -0.5px;
}

nav {
    display: flex;
    align-items: center;
    gap: 25px;
}

nav a {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #4a5568;
    font-weight: 500;
    font-size: 16px;
    transition: var(--transition);
    padding: 12px 20px;
    border-radius: var(--border-radius);
    position: relative;
    overflow: hidden;
}

nav a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    transition: left 0.3s ease;
    z-index: -1;
}

nav a:hover::before, nav a.active::before {
    left: 0;
}

nav a:hover, nav a.active {
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-medium);
}

/* Enhanced cart page styles */
.cart-page-container {
    max-width: 1400px;
    margin: 3rem auto;
    padding: 0 2rem;
    position: relative;
}

.cart-page-container::before {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    right: -20px;
    bottom: -20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    z-index: -1;
}

.cart-page-container h2 {
    margin-bottom: 2rem;
    color: var(--white);
    font-size: 2.5rem;
    font-weight: 700;
    text-align: center;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.cart-page-container h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #ffd93d);
    border-radius: 2px;
}

.user-actions {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    padding: 20px 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-light);
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user-actions h2 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin: 0;
}

.user-actions h2::after {
    display: none;
}

.logout-btn {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    box-shadow: var(--box-shadow-light);
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-medium);
}

.cart-page {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

@media (min-width: 1200px) {
    .cart-page {
        grid-template-columns: 2fr 1fr;
    }
}

/* Enhanced cart table */
.cart-table {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-heavy);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.cart-table table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 20px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.cart-table td {
    padding: 20px 15px;
    border-bottom: 1px solid var(--medium-gray);
    vertical-align: middle;
    font-size: 0.95rem;
}

.cart-table tr:hover {
    background: rgba(76, 175, 80, 0.05);
    transform: translateX(2px);
    transition: var(--transition);
}

.cart-table tr:last-child td {
    border-bottom: none;
}

.quantity {
    width: 70px;
    padding: 8px 12px;
    border: 2px solid var(--medium-gray);
    border-radius: 8px;
    font-size: 1rem;
    text-align: center;
    transition: var(--transition);
    background: var(--white);
}

.quantity:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.remove-btn {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 6px;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.remove-btn::before {
    content: '🗑️';
    font-size: 0.9em;
}

.remove-btn:hover {
    background: rgba(255, 87, 34, 0.1);
    transform: translateY(-1px);
}

.total-row {
    background: linear-gradient(135deg, var(--light-gray), #e8f5e8);
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--primary-color);
}

.total-row td {
    padding: 25px 15px;
    border-top: 3px solid var(--primary-color);
}

/* Enhanced customer info form */
.customer-info {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-heavy);
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: sticky;
    top: 100px;
    max-height: fit-content;
}

.customer-info h2 {
    margin-bottom: 1.5rem;
    color: var(--primary-color);
    font-size: 1.8rem;
    font-weight: 700;
    position: relative;
    padding-bottom: 10px;
}

.customer-info h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark-gray);
    font-size: 0.95rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 15px 18px;
    border: 2px solid var(--medium-gray);
    border-radius: 10px;
    font-size: 1rem;
    transition: var(--transition);
    background: var(--white);
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    transform: translateY(-2px);
}

.phone-input-group {
    position: relative;
}

.phone-prefix {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dark-gray);
    font-weight: 600;
    background: var(--light-gray);
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.phone-input-group input {
    padding-left: 70px;
}

/* Enhanced notification method styles */
.notification-method {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid var(--medium-gray);
    border-radius: var(--border-radius);
    padding: 20px;
    margin: 20px 0;
    position: relative;
    overflow: hidden;
}

.notification-method::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.notification-method h4 {
    margin-bottom: 15px;
    color: var(--black);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-top: 15px;
}

.notification-option {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 2px solid var(--medium-gray);
    border-radius: 10px;
    cursor: pointer;
    transition: var(--transition);
    background: var(--white);
    position: relative;
    overflow: hidden;
}

.notification-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(69, 160, 73, 0.1));
    transition: left 0.3s ease;
}

.notification-option:hover::before,
.notification-option.selected::before {
    left: 0;
}

.notification-option:hover,
.notification-option.selected {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-light);
}

.notification-option input[type="radio"] {
    margin-right: 10px;
    transform: scale(1.2);
    accent-color: var(--primary-color);
}

.notification-icon {
    margin-right: 8px;
    font-size: 1.2em;
    color: var(--primary-color);
}

/* Enhanced checkout button */
.checkout-btn {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--box-shadow-medium);
}

.checkout-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--secondary-color), #3e8e41);
    transition: left 0.3s ease;
}

.checkout-btn:hover::before {
    left: 0;
}

.checkout-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--box-shadow-heavy);
}

.checkout-btn i {
    margin-right: 10px;
    position: relative;
    z-index: 1;
}

.checkout-btn span {
    position: relative;
    z-index: 1;
}

/* Enhanced empty cart */
.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-heavy);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.empty-cart::before {
    content: '🛒';
    font-size: 4rem;
    display: block;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-cart h3 {
    font-size: 1.5rem;
    color: var(--dark-gray);
    margin-bottom: 1rem;
}

.empty-cart p {
    color: var(--dark-gray);
    margin-bottom: 2rem;
}

.empty-cart a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    padding: 12px 24px;
    border: 2px solid var(--primary-color);
    border-radius: 25px;
    transition: var(--transition);
    display: inline-block;
}

.empty-cart a:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

/* Enhanced error and success messages */
.error-message, .success-message {
    padding: 15px 20px;
    border-radius: var(--border-radius);
    margin: 20px 0;
    border-left: 4px solid;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.error-message {
    background: rgba(248, 215, 218, 0.9);
    color: #721c24;
    border-left-color: #dc3545;
}

.error-message::before {
    content: '⚠️';
    font-size: 1.2em;
}

.success-message {
    background: rgba(212, 237, 218, 0.9);
    color: #155724;
    border-left-color: #28a745;
}

.success-message::before {
    content: '✅';
    font-size: 1.2em;
}

/* Enhanced search container */
.search-container {
    position: relative;
    width: 350px;
}

.search-container input {
    width: 100%;
    padding: 15px 25px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    font-size: 15px;
    transition: var(--transition);
    box-shadow: var(--box-shadow-light);
}

.search-container input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

.search-container button {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition);
}

.search-container button:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* Auth buttons */
.auth-buttons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.auth-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.login-btn {
    background: rgba(255, 255, 255, 0.1);
    color: #4a5568;
    border: 2px solid rgba(102, 126, 234, 0.3);
    backdrop-filter: blur(10px);
}

.login-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    transition: left 0.3s ease;
    z-index: -1;
}

.login-btn:hover::before {
    left: 0;
}

.login-btn:hover {
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-medium);
}

.track-order-btn {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
}

.track-order-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
}
.suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
        }

        .suggestion-item {
            padding: 15px 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .suggestion-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .suggestion-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }


/* Responsive design */
@media (max-width: 768px) {
    .cart-page-container {
        padding: 0 1rem;
        margin: 2rem auto;
    }
    
    .cart-page {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .customer-info {
        position: static;
    }
    
    .cart-table {
        overflow-x: auto;
    }
    
    .cart-table table {
        min-width: 600px;
    }
    
    .notification-options {
        grid-template-columns: 1fr;
    }
    
    .header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-container {
        width: 100%;
        max-width: 400px;
    }
}

@media (max-width: 480px) {
    .cart-page-container h2 {
        font-size: 2rem;
    }
    
    .user-actions {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }
    
    .customer-info {
        padding: 1.5rem;
    }
    
    .form-group input,
    .form-group select {
        padding: 12px 15px;
    }
}

/* Animation keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cart-table,
.customer-info {
    animation: fadeInUp 0.6s ease-out;
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--light-gray);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--secondary-color), #3e8e41);
}
    </style>
</head>
<body>
<header>
        <div class="logo-container">
            <img src="images/vasco.jpg" alt="Pharmacy Logo">
            <div class="logo-text">Vasco Pharmacy</div>
        </div>
        
        <nav>
            <a href="index.php"><i class="fas fa-home"></i> <span>Home</span></a>
            <a href="product.php" class="active"><i class="fas fa-shopping-cart"></i> <span>Products</span></a>
            <a href="about.php"><i class="fas fa-info-circle"></i> <span>About</span></a>
            <a href="contact.php"><i class="fas fa-envelope"></i> <span>Contact</span></a>
            <a href="cart.php"><i class="fas fa-shopping-basket"></i> <span>Cart</span></a>
            
            <div class="search-container">
            <form action="search.php" method="get" onsubmit="return vsearch()">
    <input type="text" name="query" id="search-input" placeholder="Search for products..." onkeyup="fetchSuggestions()">
    <button type="submit"><i class="fas fa-search"></i></button>
    <div id="suggestions" class="suggestions"></div>
</form>
            </div>
            
            </div>
            <div class="auth-buttons">
                <a href="userlogin.php" class="auth-btn login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            
            <a href="my_orders.php" class="track-order-btn">
                <i class="fas fa-map-marker-alt"></i> <span>Track Order</span>
            </a>
            </div>
        </nav>
    </header>
    
<div id="cart-page" class="cart-page-container">
<div class="user-actions">
<?php if (isset($_SESSION['user_id'])): ?>
        <h2><span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    <?php endif; ?>
</div>

    <div class="cart-page">
        <!-- Cart Table -->
        <div class="cart-table">
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
                    <p>Your cart is empty. <a href="product.php">Continue shopping.</a></p>
                </div>
                
            <?php else : ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($cartProducts as $product) :
                            $productId = $product['id'];
                            $name = htmlspecialchars($product['name']);
                            $price = $product['price'];
                            $quantity = $_SESSION['cart'][$productId]['quantity'];
                            $subTotal = $price * $quantity;
                            $total += $subTotal;
                        ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td>TShs<?php echo number_format($price, 2); ?></td>
                                <td>
                                    <input type="number" class="quantity" data-product-id="<?php echo $productId; ?>" value="<?php echo $quantity; ?>" min="1">
                                </td>
                                <td>TShs<?php echo number_format($subTotal, 2); ?></td>
                                <td>
                                    <a href="cart.php?action=remove&id=<?php echo $productId; ?>" class="remove-btn">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3">Total</td>
                            <td>TShs<?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Customer Information Form -->
      <!-- Replace the customer info form section in cart.php with this: -->
<?php if (!empty($cartProducts)) : ?>
    <div class="customer-info">
        <h2>Customer Information</h2>
        <form method="POST" action="cart.php" id="checkout-form">
            <?php
            // Fetch user details from database
            $userStmt = $conn->prepare("SELECT full_name, email, phone FROM users WHERE user_id = ?");
            $userStmt->bind_param('i', $userId);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            $userData = $userResult->fetch_assoc();
            $userStmt->close();
            
            // Split full name into first and last name
            $nameParts = explode(' ', $userData['full_name'], 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            ?>
            
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <select id="city" name="city" required>
                    <?php foreach ($locations as $city => $state): ?>
                    <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="state">State:</label>
                <select id="state" name="state" required>
                    <?php foreach ($locations as $city => $state): ?>
                    <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <div class="phone-input-group">
                    <span class="phone-prefix">+255</span>
                    <?php 
                    // Extract phone number without country code if it exists
                    $phone = $userData['phone'];
                    if (strpos($phone, '+255') === 0) {
                        $phone = substr($phone, 4);
                    }
                    ?>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="712345678" pattern="[0-9]{9}" required>
                </div>
                <small style="color: #6c757d; font-size: 0.875rem;">Enter 9 digits without the country code</small>
            </div>

            <!-- Rest of the form remains the same -->
            <div class="notification-method">
                <h4><i class="fas fa-bell notification-icon"></i>How would you like to receive order updates?</h4>
                <div class="notification-options">
                    <label class="notification-option">
                        <input type="radio" name="notification_method" value="email" checked>
                        <i class="fas fa-envelope notification-icon"></i>
                        Email Only
                    </label>
                    <label class="notification-option">
                        <input type="radio" name="notification_method" value="sms">
                        <i class="fas fa-sms notification-icon"></i>
                        SMS Only
                    </label>
                    <label class="notification-option">
                        <input type="radio" name="notification_method" value="whatsapp">
                        <i class="fab fa-whatsapp notification-icon"></i>
                        WhatsApp
                    </label>
                    <label class="notification-option">
                        <input type="radio" name="notification_method" value="both">
                        <i class="fas fa-bell-ring notification-icon"></i>
                        Email + SMS
                    </label>
                </div>
            </div>

            <button type="submit" class="checkout-btn">
                <i class="fas fa-shopping-cart"></i> Place Order
            </button>
        </form>
    </div>
<?php endif; ?>
        </div>
    </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('change', function() {
        const productId = this.dataset.productId;
        const newQuantity = parseInt(this.value);
        if (newQuantity > 0) {
            window.location.href = `cart.php?action=update&id=${productId}&quantity=${newQuantity}`;
        } else {
            this.value = 1; // Reset to minimum quantity if invalid
        }
    });
});

function fetchSuggestions() {
    var query = document.getElementById('search-input').value;

    if (query.trim() === '') {
        document.getElementById('suggestions').style.display = 'none';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search.php?query=' + encodeURIComponent(query), true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var suggestions = JSON.parse(xhr.responseText);
            var suggestionsContainer = document.getElementById('suggestions');
            suggestionsContainer.innerHTML = '';  // Clear any previous suggestions

            if (suggestions.length > 0) {
                suggestionsContainer.style.display = 'block';

                suggestions.forEach(function(suggestion) {
                    var suggestionElement = document.createElement('div');
                    suggestionElement.className = 'suggestion-item';
                    suggestionElement.innerHTML = `<a href="product_details.php?id=${suggestion.id}">${suggestion.name}</a>`;
                    suggestionsContainer.appendChild(suggestionElement);
                });
            } else {
                suggestionsContainer.style.display = 'none';
            }
        }
    };

    xhr.send();
}

function vsearch() {
    var query = document.getElementById('search-input').value;
    if (query.trim() === '') {
        alert('Please enter a search term');
        return false;
    }
    return true;
}

document.getElementById('search-input').addEventListener('input', fetchSuggestions);

document.addEventListener('DOMContentLoaded', function() {
    var sliders = document.querySelectorAll('.product-slider');

    sliders.forEach(function(slider) {
        var slides = slider.querySelectorAll('.product-slide');
        var index = 0;

        function moveSlides() {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(-${index * 100}%)`;
            });
        }

        function nextSlide() {
            index = (index + 1) % slides.length;
            moveSlides();
        }

        setInterval(nextSlide, 3000);

        // Initialize first position
        moveSlides();
    });
});
document.querySelectorAll('input[name="notification_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.notification-option').forEach(option => {
                    option.classList.remove('selected');
                });
                this.closest('.notification-option').classList.add('selected');
            });
        });

        // Initialize selected state
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="notification_method"]:checked');
            if (checkedRadio) {
                checkedRadio.closest('.notification-option').classList.add('selected');
            }
        });

        // Format phone number input
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length > 9) {
                value = value.slice(0, 9);
            }
            e.target.value = value;
        });

        // Form validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            if (phone.length !== 9) {
                e.preventDefault();
                alert('Please enter a valid 9-digit phone number.');
                return false;
            }
        });

</script>
</body>
</html>