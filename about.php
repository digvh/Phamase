<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Online Pharmacy</title>
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

        
        main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .about-image {
            background: url('images/faq.jpg') center/cover;
            border-radius: 8px;
            min-height: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .text-about {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .text-about h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #4b6cb7;
        }
        
        .text-about p {
            margin-bottom: 1rem;
            text-align: justify;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card i {
            font-size: 2.5rem;
            color: #4b6cb7;
            margin-bottom: 1rem;
        }
        
        footer {
            background-color: #1e3a8a;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 30px;
            text-align: left;
        }

        .footer-section h3 {
            margin-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
            display: inline-block;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #dbeafe;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: white;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-icons a {
            color: white;
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-5px);
        }

        .copyright {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #3b82f6;
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
            
            .about-container {
                grid-template-columns: 1fr;
            }
            
            .about-image {
                min-height: 300px;
                order: -1;
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
        <div class="about-container">
            <div class="text-about">
                <h2>About Vasco Pharmaceutical</h2>
                <p>Welcome to Vasco Pharmaceutical's online pharmacy platform. We are committed to providing quality health products, medical equipment, and pharmaceutical services through our convenient digital storefront.</p>
                <p>Vasco Pharmaceutical Company Ltd was founded in the year 2000 with a mission to improve healthcare accessibility by importing high-quality medical supplies and pharmaceuticals. For over two decades, the company has operated with a focus on building strong relationships with suppliers and understanding the regulatory landscapes of various countries.</p>
                <p>In 2014, Vasco Pharmaceutical Company Ltd officially registered its operations, marking a significant milestone in its history. This registration formalized the company's commitment to industry standards and regulations, paving the way for more structured operations.</p>
                
                <h3>Our Online Pharmacy Services</h3>
                <p>As Tanzania's healthcare landscape evolves, we've expanded our services to include a comprehensive online pharmacy platform offering:</p>
                
                <div class="features">
                    <div class="feature-card">
                        <i class="fa fa-medkit"></i>
                        <h4>Prescription Medications</h4>
                        <p>Verified prescription drugs delivered to your doorstep with professional pharmacist consultation.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fa fa-heartbeat"></i>
                        <h4>Medical Equipment</h4>
                        <p>From blood pressure monitors to diabetes care supplies, we carry quality medical devices.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fa fa-truck"></i>
                        <h4>Nationwide Delivery</h4>
                        <p>Reliable delivery services across Tanzania, including Dar es Salaam, Arusha, Mwanza and Kahama.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fa fa-shield"></i>
                        <h4>Quality Assurance</h4>
                        <p>All products are sourced from licensed manufacturers and stored under optimal conditions.</p>
                    </div>
                </div>
                
                <p>Vasco Pharmaceutical Company Ltd has grown significantly since its inception, establishing a strong presence within Tanzania. Currently, the company operates six branches strategically located across four key regions (Dar es Salaam, Arusha, Mwanza and Kahama). These branches support our online operations, ensuring prompt delivery and local customer support.</p>
                <p>Looking ahead, Vasco Pharmaceutical Company Ltd is committed to furthering its reach and impact. We are actively expanding our online services throughout Tanzania, ensuring that high-quality pharmaceutical care is accessible to even more communities through both digital and physical channels.</p>
            </div>
            <div class="about-image"></div>
        </div>
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