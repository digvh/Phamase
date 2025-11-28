<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vasco Online Pharmacy</title>
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


        /* Enhanced main styles */
        main {
            margin-top: 0;
        }

        /* Enhanced banner */
        .banner {
            width: 100%;
            position: relative;
            overflow: hidden;
            text-align: center;
            height: 500px;
        }

        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .indexSlides {
            display: none;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1.1);
        }

        .indexSlides.active {
            display: block;
            opacity: 1;
            transform: scale(1);
        }

        .indexSlides img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
        }

        .banner-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 10;
            width: 90%;
            max-width: 800px;
        }

        .banner-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .banner-content p {
            font-size: 1.4rem;
            margin-bottom: 30px;
            font-weight: 300;
            opacity: 0.95;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .cta {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2, #667eea);
            transition: left 0.3s ease;
        }

        .cta:hover::before {
            left: 0;
        }

        .cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.5);
        }

        .cta span {
            position: relative;
            z-index: 1;
        }

        /* Enhanced featured products */
        .featured-products {
            padding: 80px 40px;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 40px 20px;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .featured-products::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .featured-products > h2 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .product-slider {
            display: flex;
            overflow-x: auto;
            gap: 30px;
            padding: 30px 0;
            scrollbar-width: none;
            position: relative;
            z-index: 1;
        }

        .product-slider::-webkit-scrollbar {
            display: none;
        }

        .product-slide {
            min-width: 280px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .product-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-slide:hover::before {
            opacity: 1;
        }

        .product-slide:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .product-slide img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-slide:hover img {
            transform: scale(1.05);
        }

        .product-slide h3 {
            padding: 20px 15px 10px;
            color: #2d3748;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .more-products-btn {
            display: inline-block;
            margin: 15px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .more-products-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2, #667eea);
            transition: left 0.3s ease;
        }

        .more-products-btn:hover::before {
            left: 0;
        }

        .more-products-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .more-products-btn span {
            position: relative;
            z-index: 1;
        }

        /* Enhanced values container */
        .values-contaiener-1 {
            max-width: 100%;
            margin: 60px auto;
            padding: 0 20px;
            position: relative;
        }

        .values-contaiener-1 img {
            width: 100%;
            height: auto;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .values-contaiener-1 img:hover {
            transform: scale(1.02);
        }

        /* Enhanced FAQ section */
        .faq {
            padding: 80px 40px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 40px 20px;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .faq h2 {
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 50px;
            font-size: 2.8rem;
            font-weight: 800;
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-item {
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .faq-question {
            display: flex;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            cursor: pointer;
            font-weight: 600;
            color: #2d3748;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
        }

        .faq-question img {
            width: 28px;
            margin-right: 20px;
            filter: hue-rotate(200deg) saturate(1.5);
        }

        .faq-answer {
            padding: 25px 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #4a5568;
            line-height: 1.7;
        }

        /* Enhanced Footer */
        footer {
            background: linear-gradient(135deg, #2d3748, #4a5568);
            color: white;
            padding: 60px 40px 30px;
            text-align: center;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="footerPattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23footerPattern)"/></svg>');
            pointer-events: none;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .footer-section {
            flex: 1;
            min-width: 280px;
            margin-bottom: 40px;
            text-align: left;
        }

        .footer-section h3 {
            margin-bottom: 25px;
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-section ul li a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            font-size: 24px;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .social-links a:hover {
            transform: translateY(-5px) scale(1.1);
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .copyright {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 1;
        }

        /* Enhanced Responsive styles */
        @media (max-width: 1024px) {
            .search-container {
                width: 280px;
            }
            
            .banner-content h1 {
                font-size: 2.8rem;
            }
            
            .featured-products {
                padding: 60px 30px;
                margin: 30px 15px;
            }
        }

        @media (max-width: 768px) {
            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
            
            .search-container {
                width: 100%;
                margin: 10px 0;
            }
            
            .banner-content h1 {
                font-size: 2.2rem;
            }
            
            .banner-content p {
                font-size: 1.1rem;
            }
            
            .banner {
                height: 400px;
            }

            .featured-products {
                padding: 50px 20px;
                margin: 20px 10px;
            }

            .product-slider {
                gap: 20px;
            }

            .product-slide {
                min-width: 250px;
            }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                padding: 15px;
            }
            
            nav {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            
            .banner {
                height: 300px;
            }
            
            .banner-content {
                width: 95%;
                padding: 20px;
            }
            
            .banner-content h1 {
                font-size: 1.8rem;
            }

            .banner-content p {
                font-size: 1rem;
            }

            .featured-products {
                padding: 40px 15px;
                margin: 15px 5px;
            }

            .featured-products > h2 {
                font-size: 2rem;
            }

            .faq {
                padding: 50px 20px;
                margin: 20px 10px;
            }
        }

        /* Loading animation */
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }

        .loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
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
        <section class="banner">
            <div class="slideshow-container">
                <div class="indexSlides fade active">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1353&q=80" alt="Slide 1">
                </div>
                <div class="indexSlides fade">
                    <img src="images/hospital2.jpg" alt="Slide 2">
                </div>
                <div class="indexSlides fade">
                    <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80" alt="Slide 3">
                </div>
            </div>
            
            <div class="banner-content">
                <h1>Welcome to Vasco Online Pharmacy</h1>
                <p>Your health, our priority - we are the best company to provide medical products and services</p>
                <a href="product.php" class="cta"><span>Shop Now</span></a>
            </div>
        </section>
        
        <?php
        require 'db.php';
        echo "<section class='featured-products'>";
        echo "<h2>The categories of our products</h2>";
        echo "<div class='product-slider'>";

        $stmt = $conn->prepare("SELECT categories_name, image FROM categore LIMIT 10");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $category_name = $row['categories_name'];
            $category_image = $row['image'];
            echo "<div class='product-slide'>";
            echo "<img src='" . $category_image . "' alt='" . $category_name . "'>";
            echo "<h3>" . $category_name . "</h3>";
            echo "<a href='category_products.php?category=" . urlencode($category_name) . "' class='more-products-btn'><span>More Products</span></a>";

            echo "</div>";
        }

        echo "</div>";
        echo "</section>";
        ?>
        
        <div class="values-contaiener-1">
        <img src="images/posterhome.jpg" alt="poster">
        </div>
        
        <?php
        require 'db.php';
        echo "<section class='featured-products'>";
        echo "<h2>OUR LATEST PRODUCTS</h2>";
        echo "<div class='product-slider'>";

        $stmt = $conn->prepare("SELECT * FROM products LIMIT 10");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $product_name = $row['name'];
            $product_image = $row['image'];
            echo "<div class='product-slide'>";
            echo "<img src='" . $product_image . "' alt='" . $product_name . "'>";
            echo "<h3>" . $product_name . "</h3>";
            echo "<a href='product_details.php?action=add&id=" . $row['id'] . "' class='more-products-btn'><span>View Product</span></a>";
            echo "</div>";
        }

        echo "</div>";
        echo "</section>";
        ?>
        
        <section class="faq">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" alt="Return">
                        What is your return policy?
                    </div>
                    <div class="faq-answer">
                        <p>We offer a 30-day return policy on all items. Products must be unopened and in their original packaging. For prescription medications, returns are subject to regulatory restrictions.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" alt="Contact">
                        How can I contact customer support?
                    </div>
                    <div class="faq-answer">
                        <p>You can contact us through our website's contact form, call our 24/7 hotline at 123-456-7890, or visit any of our physical locations. Our support team is always ready to help.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" alt="Order">
                        How can I Place an Order?
                    </div>
                    <div class="faq-answer">
                        <p>You can place an order by visiting our branches directly or ordering online through our website. Online orders offer convenient delivery options and secure payment methods.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <img src="https://cdn-icons-png.flaticon.com/512/159/159604.png" alt="Discount">
                        Are there any Discounts for Bulk Orders?
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer attractive discounts for bulk orders that meet our company's standard requirements. Contact our sales team for personalized pricing on large quantity purchases.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="product.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="cart.php">Shopping Cart</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Product Categories</h3>
                <ul>
                    <li><a href="category_products.php?category=Medicine">Medicines</a></li>
                    <li><a href="category_products.php?category=Medical%20Devices">Medical Devices</a></li>
                    <li><a href="category_products.php?category=In-vitro%20Diagnostics">In-vitro Diagnostics</a></li>
                    <li><a href="category_products.php?category=Hospital%20Equipments">Hospital Equipments</a></li>
                    <li><a href="category_products.php?category=Personal%20Care%20Items">Personal Care Items</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Information</h3>
                <p>123 Health Street, Medical City</p>
                <p>Phone: (123) 456-7890</p>
                <p>Email: info@vasco-pharmacy.com</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 Vasco Pharmaceutical Company Limited. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Enhanced slideshow functionality
        document.addEventListener('DOMContentLoaded', function() {
            let slideIndex = 0;
            const slides = document.querySelectorAll('.indexSlides');
            const totalSlides = slides.length;
            
            function showSlides() {
                slides.forEach((slide, index) => {
                    slide.classList.remove('active');
                    if (index === slideIndex) {
                        setTimeout(() => {
                            slide.classList.add('active');
                        }, 100);
                    }
                });
                
                slideIndex++;
                if (slideIndex >= totalSlides) {
                    slideIndex = 0;
                }
                
                setTimeout(showSlides, 5000);
            }
            
            if (totalSlides > 0) {
                slides[0].classList.add('active');
                setTimeout(showSlides, 5000);
            }
        });

        // Enhanced search functionality
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

        // Close suggestions when clicking outside
        document.addEventListener('click', function(event) {
            const searchContainer = document.querySelector('.search-container');
            const suggestions = document.getElementById('suggestions');
            
            if (!searchContainer.contains(event.target)) {
                suggestions.style.display = 'none';
            }
        });

        // Enhanced product slider with auto-scroll
        document.addEventListener('DOMContentLoaded', function() {
            var sliders = document.querySelectorAll('.product-slider');

            sliders.forEach(function(slider) {
                let isScrolling = false;
                let scrollAmount = 0;
                
                function autoScroll() {
                    if (!isScrolling) {
                        const maxScroll = slider.scrollWidth - slider.clientWidth;
                        
                        if (scrollAmount >= maxScroll) {
                            scrollAmount = 0;
                        } else {
                            scrollAmount += 300;
                        }
                        
                        slider.scrollTo({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    }
                }

                // Auto-scroll every 4 seconds
                const autoScrollInterval = setInterval(autoScroll, 4000);

                // Pause auto-scroll on hover
                slider.addEventListener('mouseenter', function() {
                    isScrolling = true;
                });

                slider.addEventListener('mouseleave', function() {
                    isScrolling = false;
                });

                // Update scroll amount when manually scrolled
                slider.addEventListener('scroll', function() {
                    if (isScrolling) {
                        scrollAmount = slider.scrollLeft;
                    }
                });
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.featured-products, .faq-item, .product-slide').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>