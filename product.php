<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Vasco Online Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* Enhanced body with dynamic background */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    color: #333;
    line-height: 1.6;
    overflow-x: hidden;
    position: relative;
    min-height: 100vh;
}

/* Animated background overlay */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
    z-index: -1;
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Modern glassmorphism header */
header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

header:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* Enhanced logo container with micro-animations */
.logo-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logo-container img {
    height: 55px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.logo-container img:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.3);
}

.logo-text {
    background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 800;
    font-size: 1.9rem;
    letter-spacing: -0.5px;
    position: relative;
}

.logo-text::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 2px;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.logo-container:hover .logo-text::after {
    transform: scaleX(1);
}

/* Enhanced navigation with smooth transitions */
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
    gap: 20px;
}

nav a {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #4a5568;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    padding: 14px 22px;
    border-radius: 15px;
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
    transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: -1;
}

nav a::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: all 0.4s ease;
    z-index: -1;
    transform: translate(-50%, -50%);
}

nav a:hover::before, nav a.active::before {
    left: 0;
}

nav a:hover::after, nav a.active::after {
    width: 100%;
    height: 100%;
}

nav a:hover, nav a.active {
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

nav a i {
    font-size: 18px;
    transition: transform 0.3s ease;
}

nav a:hover i {
    transform: scale(1.2);
}

/* Enhanced search container with modern styling */
.search-container {
    position: relative;
    width: 380px;
}

.search-container input {
    width: 100%;
    padding: 18px 60px 18px 25px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.search-container input::placeholder {
    color: #718096;
    font-weight: 400;
}

.search-container input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

.search-container button {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    padding: 12px;
    border-radius: 50%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.search-container button:hover {
    transform: translateY(-50%) scale(1.15);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    background: linear-gradient(135deg, #764ba2, #667eea);
}

/* Enhanced suggestions dropdown */
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


/* Enhanced auth buttons */
.auth-buttons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.auth-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 24px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.login-btn {
    background: rgba(255, 255, 255, 0.15);
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
    transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: -1;
}

.login-btn:hover::before {
    left: 0;
}

.login-btn:hover {
    color: white;
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.track-order-btn {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 14px 28px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
    transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.track-order-btn:hover::before {
    left: 0;
}

.track-order-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 45px rgba(255, 107, 107, 0.5);
}

.track-order-btn span {
    position: relative;
    z-index: 1;
}

/* Enhanced main container */
.main-container {
    display: flex;
    max-width: 1600px;
    margin: 0 auto;
    padding: 30px 20px;
    gap: 30px;
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
        .welcome-msg {
    margin-right: 15px;
    color: #4a5568;
    font-weight: 500;
    font-size: 14px;
}

.auth-btn.logout-btn {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
    border: 2px solid rgba(231, 76, 60, 0.3);
}

.auth-btn.logout-btn:hover {
    background: #e74c3c;
    color: white;
    border-color: transparent;
}

/* Adjust track order button spacing */
.auth-buttons {
    gap: 10px;
}

    
        
        /* Sidebar styles */
        .category-sidebar {
            width: 280px;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-right: 25px;
            height: fit-content;
            position: sticky;
            top: 90px;
        }
        
        .category-sidebar h2 {
            color: #2563eb;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 1.6rem;
        }
        
        .category-list {
            list-style: none;
        }
        
        .category-list li {
            margin-bottom: 12px;
        }
        
        .category-list li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: #4a5568;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .category-list li a:hover, .category-list li a.active {
            background: #f0f7ff;
            color: #2563eb;
            transform: translateX(5px);
        }
        
        .category-list li a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        /* Main content styles */
        .product-content {
            flex: 1;
        }
        
        /* Slideshow section */
        .slideshow-section {
            position: relative;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .slideshow-container {
            position: relative;
            height: 100%;
            width: 100%;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }
        
        .slide.active {
            opacity: 1;
        }
        
        .slide-1 { background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'); }
        .slide-2 { background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'); }
        .slide-3 { background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'); }
        
        .slideshow-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            width: 80%;
            max-width: 800px;
        }
        
        .slideshow-content h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .slideshow-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .slideshow-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        
        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .indicator.active {
            background: white;
            transform: scale(1.2);
        }
        
        /* Category sections */
        .category-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f4f8;
        }
        
        .section-header h2 {
            color: #2563eb;
            font-size: 1.8rem;
        }
        
        .view-all {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .view-all:hover {
            color: #2563eb;
            transform: translateX(5px);
        }
        
        /* Product grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ff6b6b;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }
        
        .product-image {
            height: 200px;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .product-image img {
            max-width: 80%;
            max-height: 80%;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-details {
            padding: 20px;
        }
        
        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            height: 50px;
            overflow: hidden;
        }
        
        .product-price {
            color: #2563eb;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-add-cart {
            flex: 1;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-add-cart:hover {
            background: #1e4bb6;
            transform: translateY(-2px);
        }
        
        .btn-view {
            width: 45px;
            height: 45px;
            background: #e2e8f0;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-view:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, #1a3a8a, #2563eb);
            color: white;
            padding: 50px 0 20px;
            margin-top: 50px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .footer-column h3 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #ff6b6b;
            border-radius: 3px;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 12px;
        }
        
        .footer-column ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer-column ul li a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: #ff6b6b;
            transform: translateY(-5px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        /* Responsive styles */
        @media (max-width: 1100px) {
            .main-container {
                flex-direction: column;
            }
            
            .category-sidebar {
                width: 100%;
                position: static;
                margin-right: 0;
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 900px) {
            header {
                flex-direction: column;
                gap: 20px;
                padding: 15px;
            }
            
            .logo-container {
                width: 100%;
                justify-content: center;
            }
            
            nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .search-container {
                width: 100%;
                max-width: 500px;
            }
            
            .slideshow-section {
                height: 350px;
            }
            
            .slideshow-content h2 {
                font-size: 2.2rem;
            }
            
            .slideshow-content p {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 600px) {
            .slideshow-section {
                height: 300px;
            }
            
            .slideshow-content h2 {
                font-size: 1.8rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            nav a span {
                display: none;
            }
            
            nav a i {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
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
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- User is logged in - show welcome message and logout -->
        <span class="welcome-msg">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="auth-btn logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    <?php else: ?>
        <!-- User not logged in - show login button -->
        <a href="userlogin.php" class="auth-btn login-btn">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
        </a>
    <?php endif; ?>
    
    <a href="my_orders.php" class="track-order-btn">
        <i class="fas fa-map-marker-alt"></i> <span>Track Order</span>
    </a>
</div>
        </nav>
    </header>
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="category-sidebar">
            <h2>Categories</h2>
            <ul class="category-list">
                <li><a href="category_products.php?category=Medicine" class="active"><i class="fas fa-pills"></i> Medicines</a></li>
                <li><a href="category_products.php?category=In-vitro%20Diagnostics"><i class="fas fa-vial"></i> In-vitro Diagnostics</a></li>
                <li><a href="category_products.php?category=Hospital%20Equipment"><i class="fas fa-procedures"></i> Hospital Equipments</a></li>
                <li><a href="category_products.php?category=Personal%20Care%20Items"><i class="fas fa-pump-soap"></i> Personal Care Items</a></li>
                <li><a href="category_products.php?category=Baby%20Care"><i class="fas fa-baby"></i> Baby Care</a></li>
                <li><a href="category_products.php?category=Vitamins%20Supplements"><i class="fas fa-capsules"></i> Vitamins & Supplements</a></li>
                <li><a href="category_products.php?category=Allergy%20Sinus"><i class="fas fa-allergies"></i> Allergy & Sinus</a></li>
                <li><a href="category_products.php?category=First%20Aid"><i class="fas fa-band-aid"></i> First Aid</a></li>
                <li><a href="category_products.php?category=Vision%20Care"><i class="fas fa-eye"></i> Vision Care</a></li>
            </ul>
        </aside>
        
        <!-- Product Content -->
        <div class="product-content">
            <!-- Slideshow Banner -->
            <section class="slideshow-section">
                <div class="slideshow-container">
                    <div class="slide slide-1 active"></div>
                    <div class="slide slide-2"></div>
                    <div class="slide slide-3"></div>
                    
                    <div class="slideshow-content">
                        <h2>Premium Healthcare Products</h2>
                        <p>Discover our wide range of pharmaceutical products to meet all your healthcare needs</p>
                    </div>
                    
                    <div class="slideshow-indicators">
                        <div class="indicator active" data-slide="0"></div>
                        <div class="indicator" data-slide="1"></div>
                        <div class="indicator" data-slide="2"></div>
                    </div>
                </div>
            </section>
            
            <!-- Product Categories from Database -->
            <?php
            require 'db.php';

            $categories = [
                'Medicines' => 'Medicine',
                'Hospital supplies' => 'Hospital supplies',
                'In-vitro Diagnostics' => 'In-vitro diagnostics',
                'Personal Care Items' => 'Personal Care Items',
                'Baby Care' => 'Baby Care',
                'Vitamins Supplements' => 'Vitamins Supplements',
                'Allergy Sinus' => 'Allergy Sinus',
                'First Aid' => 'First Aid',
                'Vision Care' => 'Vision Care'
            ];

            foreach ($categories as $category_name => $category_slug) {
                echo "<section class='category-section'>";
                echo "<div class='section-header'>";
                echo "<h2>$category_name</h2>";
                echo "<a href='category_products.php?category=" . urlencode($category_slug) . "' class='view-all'>View All <i class='fas fa-arrow-right'></i></a>";
                echo "</div>";
                
                echo "<div class='product-grid'>";

                $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE category = ? LIMIT 4");
                $stmt->bind_param('s', $category_slug);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    echo "<div class='product-image'>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "</div>";
                    echo "<div class='product-details'>";
                    echo "<h3 class='product-title'>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<div class='product-price'>TShs" . number_format($row['price'], 2) . "</div>";
                    echo "<div class='product-actions'>";
                    if (isset($_SESSION['user_id'])) {
                    echo "<a href='cart.php?action=add&id=" . $row['id'] . "' class='btn-add-cart'><i class='fas fa-cart-plus'></i> Add to Cart</a>";
                } else {
                    echo "<a href='userlogin.php?product_id=" . $row['id'] . "' class='btn-add-cart'><i class='fas fa-cart-plus'></i> Add to Cart</a>";
                }
                    echo "<a href='product_details.php?id=" . $row['id'] . "' class='btn-view'><i class='fas fa-eye'></i></a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }

                echo "</div>"; // Close product-grid
                echo "</section>";
            }
            ?>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="product.php"><i class="fas fa-chevron-right"></i> Products</a></li>
                    <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    <li><a href="cart.php"><i class="fas fa-chevron-right"></i> My Account</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Product Categories</h3>
                <ul>
                    <li><a href="category_products.php?category=Medicine"><i class="fas fa-chevron-right"></i> Medicines</a></li>
                    <li><a href="category_products.php?category=Personal%20Care%20Items"><i class="fas fa-chevron-right"></i> Personal Care</a></li>
                    <li><a href="category_products.php?category=Hospital%20Equipments"><i class="fas fa-chevron-right"></i> Hospital Equipment</a></li>
                    <li><a href="category_products.php?category=In-vitro%20Diagnostics"><i class="fas fa-chevron-right"></i> Diagnostics</a></li>
                    <li><a href="category_products.php?category=Baby%20Care"><i class="fas fa-baby"></i> Baby Care</a></li>
                    <li><a href="category_products.php?category=Vitamins%20Supplements"><i class="fas fa-capsules"></i> Vitamins & Supplements</a></li>
                    <li><a href="category_products.php?category=Allergy%20Sinus"><i class="fas fa-allergies"></i> Allergy & Sinus</a></li>
                    <li><a href="category_products.php?category=First%20Aid"><i class="fas fa-band-aid"></i> First Aid</a></li>
                    <li><a href="category_products.php?category=Vision%20Care"><i class="fas fa-eye"></i> Vision Care</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="my_orders.php"><i class="fas fa-chevron-right"></i> Track Order</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Returns & Refunds</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Shipping Policy</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> FAQs</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Contact Us</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Health Street, Medical City</a></li>
                    <li><a href="#"><i class="fas fa-phone"></i> (123) 456-7890</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> info@vasco-pharmacy.com</a></li>
                    <li><a href="#"><i class="fas fa-clock"></i> Mon-Sat: 8AM - 10PM</a></li>
                </ul>
                
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            &copy; 2023 Vasco Pharmaceutical Company Limited. All rights reserved.
        </div>
    </footer>
    
    <script>
        // Slideshow functionality
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const indicators = document.querySelectorAll('.indicator');
            let currentSlide = 0;
            
            function showSlide(index) {
                // Hide all slides
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));
                
                // Show the selected slide
                slides[index].classList.add('active');
                indicators[index].classList.add('active');
                currentSlide = index;
            }
            
            // Auto-advance slides
            function nextSlide() {
                let next = currentSlide + 1;
                if (next >= slides.length) next = 0;
                showSlide(next);
            }
            
            // Set up auto-rotation
            let slideInterval = setInterval(nextSlide, 5000);
            
            // Add click events to indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    clearInterval(slideInterval);
                    showSlide(index);
                    slideInterval = setInterval(nextSlide, 5000);
                });
            });
            
            // Pause on hover
            const slideshowContainer = document.querySelector('.slideshow-container');
            slideshowContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            slideshowContainer.addEventListener('mouseleave', () => {
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        
        // Add to cart animation
        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevent default if it's a link
                if (this.tagName === 'A') {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    
                    // Animation effect
                    this.innerHTML = '<i class="fas fa-check"></i> Added!';
                    this.style.background = '#10b981';
                    
                    // Redirect after animation
                    setTimeout(() => {
                        window.location.href = href;
                    }, 1000);
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

    </script>
</body>
</html>