<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trackingId = filter_input(INPUT_POST, 'tracking_id', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Verify tracking ID and email
    $stmt = $conn->prepare("SELECT id, tracking_id, email FROM orders WHERE tracking_id = ? AND email = ?");
    $stmt->bind_param('ss', $trackingId, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Store minimal tracking info in session
        $_SESSION['tracking_order'] = [
            'id' => $order['id'],
            'tracking_id' => $order['tracking_id'],
            'email' => $order['email']
        ];
        
        header('Location: my_orders.php');
        exit;
    } else {
        $error = "Invalid tracking ID or email. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order - Online Pharmacy</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #ff5722;
            --light-gray: #f5f5f5;
            --white: #ffffff;
            --black: #333333;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .tracking-container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
            text-align: center;
        }

        .tracking-container h1 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        .logout-btn {
    background-color: var(--accent-color);
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-left: 1rem;
}

.logout-btn:hover {
    background-color: #e64a19;
}

        .tracking-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .tracking-info p {
            margin-bottom: 0.8rem;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--black);
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .support-link {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .support-link a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .support-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="tracking-container">
        <h1>Track Your Order</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="tracking_id">Tracking ID</label>
                <input type="text" id="tracking_id" name="tracking_id" placeholder="TRK-XXXXXX" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>

            <button type="submit" class="btn">Track Order</button>
        </form>
        <div class="footer-links">
    <p>Back to the home page? <a href="index.php">Login here</a></p>
</div>
    </div>
    
</body>
</html>