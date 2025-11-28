<?php
session_start();
require 'db.php';

// Redirect to home if already logged in
if (isset($_SESSION['user_id'])) {
    // Check if coming from product page
    if (isset($_GET['product_id'])) {
        header("Location: cart.php?action=add&id=" . $_GET['product_id']);
        exit();
    }
    // Or to previous page if redirected from cart
    elseif (isset($_SESSION['redirect_url'])) {
        $redirect = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header("Location: $redirect");
        exit();
    }
    // Default to home page
    else {
        header('Location: index.php');
        exit();
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Query to get user data
    $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // Set session cookie for longer persistence
            $sessionLifetime = 86400; // 24 hours
            session_set_cookie_params($sessionLifetime);
            session_regenerate_id(true);
            
            // Redirect based on role
            if ($user['role'] == 'courier') {
                header('Location: courier_dashboard.php');
                exit();
            }
            
            // For customers, check if coming from product page
            if (isset($_GET['product_id'])) {
                header("Location: cart.php?action=add&id=" . $_GET['product_id']);
                exit();
            } 
            // Or to previous page if redirected from cart
            elseif (isset($_SESSION['redirect_url'])) {
                $redirect = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                header("Location: $redirect");
                exit();
            }
            // Default to home page
            else {
                header('Location: index.php');
                exit();
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vasco Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 60px;
            border-radius: 12px;
        }
        
        .logo h2 {
            margin-top: 10px;
            color: #2c3e50;
            font-weight: 700;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .links {
            margin-top: 20px;
            text-align: center;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .info-box {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .info-box h4 {
            color: #0c5460;
            margin-bottom: 8px;
        }
        
        .info-box p {
            color: #0c5460;
            font-size: 14px;
        }
        
        .notice {
            margin-top: 15px;
            padding: 10px;
            background: #fff3cd;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="images/vasco.jpg" alt="Vasco Pharmacy">
            <h2>Vasco Pharmacy</h2>
        </div>
        
        <?php if (isset($_GET['product_id'])): ?>
            <div class="notice">
                <p>Please login to add items to your cart</p>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <h4>Login Information</h4>
            <p><strong>Customer:</strong> Shop and purchase products<br>
            <strong>Courier:</strong> Manage deliveries and update order status</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="userlogin.php<?php echo isset($_GET['product_id']) ? '?product_id='.$_GET['product_id'] : ''; ?>" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            
            <button type="submit" class="btn">Login</button>
            
            <div class="links">
                <a href="admin_login.php">Are you an admin?</a>
                <span> | </span>
                <a href="register.php">Create an Account</a>
            </div>
        </form>
    </div>
</body>
</html>