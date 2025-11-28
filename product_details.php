<?php
session_start();
require 'db.php';

$product = null;

if (isset($_GET['id'])) {
    $productId = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id = '$productId'");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Online Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        /* Add floating particles animation */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        /* Enhanced header - KEEPING UNCHANGED */
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

        /* Enhanced Main Content */
        main {
            padding: 4rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            position: relative;
        }

        /* Breadcrumb Navigation */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 25px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: white;
        }

        .breadcrumb i {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }
        
        /* Enhanced Product Detail Card */
        .product-detail {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 0.8s ease-out;
        }

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

        /* Enhanced Product Image Section */
        .product-image-section {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9ff, #e8f0ff);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .product-image-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .product-detail img {
            width: 100%;
            max-width: 350px;
            height: auto;
            object-fit: contain;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 10px 20px rgba(102, 126, 234, 0.3));
        }

        .product-detail img:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        }

        /* Enhanced Product Info Section */
        .product-info {
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
        }

        .product-info::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 0 30px 0 100px;
        }

        .product-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .product-info h2 {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            padding: 20px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            border-left: 4px solid #667eea;
        }
        
        .product-info .price {
            color: #e74c3c;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 2rem 0;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .price::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-radius: 2px;
        }

        .original-price {
            font-size: 1.2rem;
            color: #999;
            text-decoration: line-through;
            font-weight: 400;
        }

        .discount-badge {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 15px;
        }

        /* Enhanced Add to Cart Button */
        .product-actions {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-selector label {
            font-weight: 600;
            color: #2c3e50;
        }

        .quantity-input {
            display: flex;
            align-items: center;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #667eea;
            color: white;
        }

        .quantity-input input {
            border: none;
            padding: 10px 20px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            width: 80px;
            outline: none;
        }
        
        .product-info a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
            padding: 18px 40px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.4s ease;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-info a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2, #667eea);
            transition: left 0.4s ease;
        }

        .product-info a:hover::before {
            left: 0;
        }

        .product-info a:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 45px rgba(102, 126, 234, 0.6);
        }

        .product-info a span {
            position: relative;
            z-index: 1;
        }

        .product-info a i {
            position: relative;
            z-index: 1;
            font-size: 18px;
        }

        /* Product Features */
        .product-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .feature-item i {
            color: #667eea;
            font-size: 20px;
        }

        .feature-item span {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }

        /* Enhanced Footer */
        footer {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 4rem 2rem 2rem;
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="footerPattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23footerPattern)"/></svg>');
            opacity: 0.3;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .footer-section {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .footer-section h3 {
            color: #667eea;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section li {
            margin-bottom: 0.8rem;
            transition: transform 0.3s ease;
        }

        .footer-section li:hover {
            transform: translateX(5px);
        }
        
        .footer-section a {
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }
        
        .footer-section a:hover {
            color: #667eea;
            text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }

        .footer-section a i {
            font-size: 14px;
            width: 20px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 1.5rem;
        }
        
        .social-links a {
            color: white;
            font-size: 1.8rem;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }

        .social-links a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.9rem;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
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

            .product-detail {
                max-width: 900px;
            }

            .product-info h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }
            
            nav form {
                margin: 10px 0;
                width: 100%;
            }
            
            #search-input {
                width: 100%;
            }
            
            .product-detail {
                grid-template-columns: 1fr;
                margin: 20px;
                border-radius: 20px;
            }

            .product-image-section {
                padding: 2rem;
            }

            .product-info {
                padding: 2rem;
            }

            .product-info h2 {
                font-size: 1.8rem;
            }

            .product-info .price {
                font-size: 2rem;
            }

            main {
                padding: 2rem 1rem;
            }

            .breadcrumb {
                margin: 0 20px 30px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 480px) {
            .product-info h2 {
                font-size: 1.5rem;
            }

            .product-info .price {
                font-size: 1.8rem;
            }

            .product-info a {
                padding: 15px 30px;
                font-size: 1rem;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
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
    <main>
        <div class="product-detail">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="product-info">
                <h2><?php echo $product['name']; ?></h2>
                <p class="price">TShs<?php echo number_format($product['price'], 2); ?></p>
                <a href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="product.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Return Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fa fa-map-marker"></i> 123 Pharmacy St, City</li>
                    <li><i class="fa fa-phone"></i> (123) 456-7890</li>
                    <li><i class="fa fa-envelope"></i> info@pharmacy.com</li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            &copy; <?php echo date("Y"); ?> Online Pharmacy. All rights reserved.
        </div>
    </footer>
    <script>
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
    </script>
</body>
</html>