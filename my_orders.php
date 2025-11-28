<?php
session_start();
require 'db.php';

// Session timeout configuration (30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds

// Check if session has timed out
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $session_timeout) {
        // Session has expired
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Check for valid session (either logged in user or guest tracking)
if (!isset($_SESSION['id']) && !isset($_SESSION['tracking_order'])) {
    header('Location: login.php');
    exit;
}

// Set session expiry for guest tracking sessions (shorter timeout - 15 minutes)
if (isset($_SESSION['tracking_order']) && !isset($_SESSION['id'])) {
    $guest_timeout = 15 * 60; // 15 minutes for guest sessions
    if (isset($_SESSION['guest_last_activity'])) {
        if (time() - $_SESSION['guest_last_activity'] > $guest_timeout) {
            session_unset();
            session_destroy();
            header('Location: login.php?timeout=1');
            exit;
        }
    }
    $_SESSION['guest_last_activity'] = time();
}
if (isset($_SESSION['id'])) {
    // For logged-in users
    $userId = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->bind_param('i', $userId);
} else {
    // For guest users tracking orders
    $trackingData = $_SESSION['tracking_order'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE tracking_id = ? AND email = ? ORDER BY order_date DESC");
    $stmt->bind_param('ss', $trackingData['tracking_id'], $trackingData['email']);
}

$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Online Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #ff5722;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #757575;
            --white: #ffffff;
            --black: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="80" cy="80" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }
        
        /* Enhanced header */
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
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-container img {
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
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
        .header-main {
            display: flex;
            align-items: center;
            gap: 30px;
            flex: 1;
            justify-content: space-between;
            margin-left: 40px;
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
            transition: all 0.3s ease;
            padding: 12px 20px;
            border-radius: 12px;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        nav a i {
            font-size: 18px;
        }
        
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
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .search-container input::placeholder {
            color: #718096;
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
            transition: all 0.3s ease;
        }

        .search-container button:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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

        .track-order-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            position: relative;
            overflow: hidden;
        }

        .track-order-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ee5a24, #ff6b6b);
            transition: left 0.3s ease;
        }
        
        .track-order-btn:hover::before {
            left: 0;
        }

        .track-order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        }

        .track-order-btn span {
            position: relative;
            z-index: 1;
        }
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
            transition: all 0.3s ease;
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .auth-btn span {
            position: relative;
            z-index: 1;
        }

        .auth-btn i {
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        /* Container and main content */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-header h1 {
            color: var(--white);
            font-size: 2.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin: 0;
        }

        .order-header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Real-time tracking styles */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: var(--primary-color);
            padding: 0.75rem 1.25rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .live-indicator:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary-color);
            border-radius: 50%;
            margin-right: 0.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }

        .logout-btn {
            background: rgba(255, 107, 107, 0.9);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .logout-btn:hover {
            background: #ff6b6b;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }

        /* Track order info section */
        .track-order-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .track-order-info h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .info-item p {
            color: var(--dark-gray);
            margin: 0;
            line-height: 1.6;
        }

        /* Order cards */
        .order-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .order-card.updated {
            box-shadow: 0 20px 60px rgba(76, 175, 80, 0.3);
            border-left: 6px solid var(--primary-color);
            animation: cardUpdate 0.6s ease;
        }

        @keyframes cardUpdate {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-id {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order-status {
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .status-processing {
            background: linear-gradient(135deg, #cce5ff, #74b9ff);
            color: #004085;
        }

        .status-shipped {
            background: linear-gradient(135deg, #e2e3ff, #a29bfe);
            color: #383d41;
        }

        .status-delivered {
            background: linear-gradient(135deg, #d4edda, #00b894);
            color: #155724;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #f8d7da, #e17055);
            color: #721c24;
        }

        /* Progress tracker */
        .progress-tracker {
            margin: 2rem 0;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 20px;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .progress-tracker h4 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 2rem 0;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--medium-gray), #dee2e6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .step-circle.active, .step-circle.completed {
            background: linear-gradient(135deg, var(--primary-color), #45a049);
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        .step-line {
            position: absolute;
            top: 25px;
            left: 50%;
            right: -50%;
            height: 4px;
            background: linear-gradient(to right, var(--medium-gray), #dee2e6);
            z-index: 1;
            border-radius: 2px;
        }

        .step-line.completed {
            background: linear-gradient(to right, var(--primary-color), #45a049);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        .step-text {
            font-size: 0.9rem;
            text-align: center;
            color: var(--dark-gray);
            font-weight: 600;
            max-width: 80px;
            line-height: 1.3;
        }

        /* Order meta information */
        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .meta-item {
            background: rgba(255, 255, 255, 0.7);
            padding: 1.25rem;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .meta-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .meta-label {
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.4rem;
            font-size: 1rem;
        }

        .meta-item .meta-value {
            color: var(--black);
            font-weight: 500;
        }

        /* Order items table */
        .order-items-container {
            overflow-x: auto;
            margin: 1.5rem 0;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .order-items {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            min-width: 600px;
        }

        .order-items th {
            background: linear-gradient(135deg, var(--primary-color), #45a049);
            color: white;
            padding: 1.2rem;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        .order-items td {
            padding: 1.2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--black);
            font-weight: 500;
        }

        .order-items tr:hover {
            background: rgba(76, 175, 80, 0.05);
        }

        .order-items tr:last-child td {
            border-bottom: none;
        }

        .order-total {
            font-weight: 700;
            text-align: right;
            margin-top: 1.5rem;
            font-size: 1.3rem;
            color: var(--primary-color);
            background: rgba(76, 175, 80, 0.1);
            padding: 1rem;
            border-radius: 10px;
        }

        .last-updated {
            font-size: 0.85rem;
            color: var(--dark-gray);
            margin-top: 1rem;
            font-style: italic;
            text-align: center;
            background: rgba(0, 0, 0, 0.05);
            padding: 0.75rem;
            border-radius: 10px;
        }

        /* No orders state */
        .no-orders {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-orders h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .no-orders p {
            margin-bottom: 1.5rem;
            color: var(--dark-gray);
            font-size: 1.1rem;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), #45a049);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        }

        .btn:hover {
            background: linear-gradient(135deg, #45a049, var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(76, 175, 80, 0.4);
        }

        /* Status notification */
        .status-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--primary-color), #45a049);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.4s ease;
            max-width: 350px;
        }

        .status-notification.show {
            transform: translateX(0);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--black), #2c3e50);
            color: var(--white);
            padding: 3rem 1rem;
            text-align: center;
            margin-top: 3rem;
        }

        footer a {
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Responsive styles */
        @media (max-width: 1200px) {
            .header-main {
                gap: 20px;
            }
            
            .search-container {
                width: 280px;
            }
            
            .auth-buttons {
                gap: 10px;
            }
            
            .auth-btn {
                padding: 10px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 1024px) {
            header {
                padding: 15px 20px;
            }
            
            .header-main {
                gap: 15px;
                margin-left: 20px;
            }
            
            .search-container {
                width: 250px;
            }
            
            .auth-btn span {
                display: none;
            }
            
            .auth-btn {
                padding: 12px;
                border-radius: 50%;
                min-width: 44px;
                justify-content: center;
            }
            
            .container {
                padding: 0 1.5rem;
            }
            
            .order-header h1 {
                font-size: 2rem;
            }
            
            .info-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 12px 15px;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .logo-container {
                gap: 10px;
            }
            
            .logo-container img {
                height: 40px;
            }

            .step-line {
                display: none;
            }
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
            <div class="auth-buttons">
                <a href="login.php" class="auth-btn login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            
            <a href="my_orders.php" class="track-order-btn">
                <i class="fas fa-map-marker-alt"></i> <span>Track Order</span>
            </a>
            </div>
        </nav>
    </header>
<div class="container">
    <div class="order-header">
        <h1>My Orders</h1>
        <div>
            <span class="live-indicator">
                <span class="live-dot"></span>
                Live Tracking
            </span>
            <?php if (isset($_SESSION['tracking_order'])): ?>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="track-order-info">
        <h2>Order Tracking Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Need Help?</span>
                <p>Contact our support team at support@pharmacy.com or call +255 123 456 789</p>
            </div>
            <div class="info-item">
                <span class="info-label">Tracking Your Order</span>
                <p>Use your tracking ID and the email address you provided during checkout</p>
            </div>
            <div class="info-item">
                <span class="info-label">Delivery Time</span>
                <p>Typically 2-5 business days depending on your location</p>
            </div>
            <div class="info-item">
                <span class="info-label">Real-time Updates</span>
                <p>Your order status updates automatically - no need to refresh the page!</p>
            </div>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="no-orders">
            <p>You have no orders yet.</p>
            <p>Start shopping to see your orders here!</p>
            <a href="product.php" class="btn">Shop Now</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            // Calculate order total
            $stmt = $conn->prepare("SELECT SUM(price * quantity) as total FROM order_items WHERE order_id = ?");
            $stmt->bind_param('i', $order['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $total = $result->fetch_assoc()['total'];
            $stmt->close();
            
            // Get status class
            $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order['order_status']));
            ?>
            
            <div class="order-card" data-order-id="<?php echo $order['id']; ?>" data-current-status="<?php echo $order['order_status']; ?>">
                <div class="order-card-header">
                    <div class="order-id">Order #<?php echo $order['id']; ?></div>
                    <div class="order-status <?php echo $statusClass; ?>" id="status-<?php echo $order['id']; ?>">
                        <?php echo $order['order_status']; ?>
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="progress-tracker">
                    <h4>Order Progress</h4>
                    <div class="progress-steps" id="progress-<?php echo $order['id']; ?>">
                        <div class="progress-step">
                            <div class="step-circle <?php echo in_array($order['order_status'], ['Pending', 'Processing', 'Shipped', 'Delivered']) ? 'completed' : ''; ?>">1</div>
                            <div class="step-text">Order Placed</div>
                        </div>
                        <div class="step-line <?php echo in_array($order['order_status'], ['Processing', 'Shipped', 'Delivered']) ? 'completed' : ''; ?>"></div>
                        <div class="progress-step">
                            <div class="step-circle <?php echo in_array($order['order_status'], ['Processing', 'Shipped', 'Delivered']) ? 'completed' : ''; ?>">2</div>
                            <div class="step-text">Processing</div>
                        </div>
                        <div class="step-line <?php echo in_array($order['order_status'], ['Shipped', 'Delivered']) ? 'completed' : ''; ?>"></div>
                        <div class="progress-step">
                            <div class="step-circle <?php echo in_array($order['order_status'], ['Shipped', 'Delivered']) ? 'completed' : ''; ?>">3</div>
                            <div class="step-text">Shipped</div>
                        </div>
                        <div class="step-line <?php echo $order['order_status'] === 'Delivered' ? 'completed' : ''; ?>"></div>
                        <div class="progress-step">
                            <div class="step-circle <?php echo $order['order_status'] === 'Delivered' ? 'completed' : ''; ?>">4</div>
                            <div class="step-text">Delivered</div>
                        </div>
                    </div>
                </div>
                
                <div class="order-meta">
                    <div class="meta-item">
                        <span class="meta-label">Tracking ID</span>
                        <?php echo $order['tracking_id'] ?? 'N/A'; ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Order Date</span>
                        <?php echo date('F j, Y', strtotime($order['order_date'])); ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Customer</span>
                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Delivery To</span>
                        <?php echo htmlspecialchars($order['city'] . ', ' . $order['state']); ?>
                    </div>
                </div>
                
                <table class="order-items">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT products.name, order_items.quantity, order_items.price 
                                              FROM order_items 
                                              JOIN products ON order_items.product_id = products.id 
                                              WHERE order_items.order_id = ?");
                        $stmt->bind_param('i', $order['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $items = $result->fetch_all(MYSQLI_ASSOC);
                        $stmt->close();
                        
                        foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>TShs<?php echo number_format($item['price'], 2); ?></td>
                                <td>TShs<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="order-total">
                    Order Total: TShs<?php echo number_format($total, 2); ?>
                </div>
                
                <div class="last-updated" id="updated-<?php echo $order['id']; ?>">
                    Last updated: <?php echo date('F j, Y g:i A', strtotime($order['updated_at'] ?? $order['order_date'])); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Status update notification -->
<div id="status-notification" class="status-notification">
    <div id="notification-message"></div>
</div>



<script>
// Real-time order tracking functionality
let lastUpdateTime = Date.now();

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
            suggestionsContainer.innerHTML = '';

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

// Real-time order status checking
function checkOrderUpdates() {
    const orderCards = document.querySelectorAll('.order-card[data-order-id]');
    const orderIds = Array.from(orderCards).map(card => card.getAttribute('data-order-id'));
    
    if (orderIds.length === 0) return;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'check_order_updates.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const updates = JSON.parse(xhr.responseText);
                updates.forEach(update => {
                    updateOrderStatus(update);
                });
            } catch (e) {
                console.error('Error parsing order updates:', e);
            }
        }
    };
    
    xhr.send(JSON.stringify({
        order_ids: orderIds,
        last_update: lastUpdateTime
    }));
}

function updateOrderStatus(update) {
    const orderCard = document.querySelector(`[data-order-id="${update.order_id}"]`);
    if (!orderCard) return;
    
    const currentStatus = orderCard.getAttribute('data-current-status');
    
    if (currentStatus !== update.order_status) {
        // Update status display
        const statusElement = document.getElementById(`status-${update.order_id}`);
        if (statusElement) {
            // Remove old status class
            statusElement.className = statusElement.className.replace(/status-\w+/g, '');
            // Add new status class
            const newStatusClass = 'status-' + update.order_status.toLowerCase().replace(/\s+/g, '-');
            statusElement.className += ' ' + newStatusClass;
            statusElement.textContent = update.order_status;
        }
        
        // Update progress tracker
        updateProgressTracker(update.order_id, update.order_status);
        
        // Update last updated time
        const updatedElement = document.getElementById(`updated-${update.order_id}`);
        if (updatedElement) {
            updatedElement.textContent = `Last updated: ${new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            })}`;
        }
        
        // Add visual feedback
        orderCard.classList.add('updated');
        setTimeout(() => {
            orderCard.classList.remove('updated');
        }, 3000);
        
        // Show notification
        showStatusNotification(update.order_id, update.order_status);
        
        // Update data attribute
        orderCard.setAttribute('data-current-status', update.order_status);
    }
}

function updateProgressTracker(orderId, status) {
    const progressContainer = document.getElementById(`progress-${orderId}`);
    if (!progressContainer) return;
    
    const steps = progressContainer.querySelectorAll('.step-circle');
    const lines = progressContainer.querySelectorAll('.step-line');
    
    // Reset all steps and lines
    steps.forEach(step => {
        step.classList.remove('completed', 'active');
    });
    lines.forEach(line => {
        line.classList.remove('completed');
    });
    
    // Update based on status
    const statusMap = {
        'Pending': 1,
        'Processing': 2,
        'Shipped': 3,
        'Delivered': 4
    };
    
    const currentStep = statusMap[status] || 1;
    
    // Mark completed steps
    for (let i = 0; i < currentStep; i++) {
        if (steps[i]) {
            steps[i].classList.add('completed');
        }
        if (lines[i] && i < currentStep - 1) {
            lines[i].classList.add('completed');
        }
    }
}

function showStatusNotification(orderId, status) {
    const notification = document.getElementById('status-notification');
    const message = document.getElementById('notification-message');
    
    message.innerHTML = `
        <strong>Order #${orderId} Updated!</strong><br>
        Status changed to: ${status}
    `;
    
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 5000);
}

// Start real-time checking when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check for updates every 10 seconds
    setInterval(checkOrderUpdates, 10000);
    
    // Initial check after 2 seconds
    setTimeout(checkOrderUpdates, 2000);
});

// Update lastUpdateTime periodically
setInterval(() => {
    lastUpdateTime = Date.now();
}, 60000); // Update every minute
</script>
</body>
</html>