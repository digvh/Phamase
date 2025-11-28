<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products - Online Pharmacy</title>
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
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
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

        /* Responsive styles for auth buttons */
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
        }

        /* Enhanced Main Content */
        main {
            padding: 4rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }

        main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            z-index: -1;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 3rem;
            text-align: center;
            position: relative;
            letter-spacing: -1px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Enhanced Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.5rem;
            margin-top: 2rem;
        }
        
        .product-item {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .product-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
            pointer-events: none;
        }
        
        .product-item:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .product-item:hover::before {
            opacity: 1;
        }
        
        .product-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.4s ease;
            position: relative;
            z-index: 2;
        }

        .product-item:hover img {
            transform: scale(1.1);
        }
        
        .product-item h3 {
            padding: 1.5rem 1.5rem 0.5rem;
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 700;
            position: relative;
            z-index: 2;
            line-height: 1.4;
        }
        
        .product-item p {
            padding: 0 1.5rem;
            color: #e74c3c;
            font-weight: 800;
            font-size: 1.5rem;
            margin-top: auto;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-actions {
            display: flex;
            gap: 15px;
            padding: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .btn-add-cart {
            flex: 1;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-add-cart::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2, #667eea);
            transition: left 0.3s ease;
        }
        
        .btn-add-cart:hover::before {
            left: 0;
        }
        
        .btn-add-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-add-cart i, .btn-add-cart span {
            position: relative;
            z-index: 1;
        }
        
        .btn-view {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            color: #667eea;
            font-size: 1.2rem;
            position: relative;
            overflow: hidden;
        }

        .btn-view::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scale(0);
            transition: transform 0.3s ease;
            border-radius: 15px;
        }
        
        .btn-view:hover::before {
            transform: scale(1);
        }

        .btn-view:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
            color: white;
            border-color: transparent;
        }

        .btn-view i {
            position: relative;
            z-index: 1;
        }

        /* Enhanced Footer */
        footer {
            background: linear-gradient(135deg, #1a202c, #2d3748);
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
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            position: relative;
            z-index: 1;
        }
        
        .footer-section h3 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            position: relative;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
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
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .footer-section a:hover {
            color: #667eea;
            text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }
        
        .social-links {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .social-links a {
            color: white;
            font-size: 1.8rem;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .social-links a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1rem;
            color: #a0aec0;
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
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
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 2rem;
            }

            main {
                padding: 2rem 1rem;
            }

            h2 {
                font-size: 2rem;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-actions {
                flex-direction: column;
                gap: 12px;
            }

            .btn-view {
                width: 100%;
                height: 50px;
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
    <main>
        <?php
        require 'db.php';

        if (isset($_GET['category'])) {
            $category_name = urldecode($_GET['category']);
            echo "<h2>Products in " . htmlspecialchars($category_name) . "</h2>";

            $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE category = ?");
            $stmt->bind_param('s', $category_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='product-grid'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>TShs" . number_format($row['price'], 2) . "</p>";
                    echo "<div class='product-actions'>";
                    echo "<a href='cart.php?action=add&id=" . $row['id'] . "' class='btn-add-cart'><i class='fas fa-cart-plus'></i> Add to Cart</a>";
                    echo "<a href='product_details.php?id=" . $row['id'] . "' class='btn-view'><i class='fas fa-eye'></i></a>";
                    echo "</div>";
                    echo "</div>";

                }
                echo "</div>";
            } else {
                echo "<p>No products found in this category.</p>";
            }
        } else {
            echo "<p>No category selected.</p>";
        }
        ?>
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
                </div>ert5569
            </div>
        </div>
        
        <div class="copyright">
            &copy; <?php echo date("Y"); ?> Online Pharmacy. All rights reserved.
        </div>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>