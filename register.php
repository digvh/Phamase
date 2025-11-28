<?php
session_start();
require 'db.php';

// Redirect to home if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == 'courier') {
        header('Location: courier_dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

$error = '';
$success = '';

// Define allowed roles for registration
$allowedRoles = ['customer', 'courier'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $full_name = trim($conn->real_escape_string($_POST['full_name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $phone = trim($conn->real_escape_string($_POST['phone']));
    $address = trim($conn->real_escape_string($_POST['address']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) && in_array($_POST['role'], $allowedRoles) ? $_POST['role'] : 'customer';
    
    // Additional courier-specific fields
    $vehicle_type = $role == 'courier' ? trim($conn->real_escape_string($_POST['vehicle_type'])) : null;
    $license_plate = $role == 'courier' ? trim($conn->real_escape_string($_POST['license_plate'])) : null;
    
    // Validate inputs
    if (empty($full_name) || empty($email) || empty($phone) || empty($address)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif ($role == 'courier' && (empty($vehicle_type) || empty($license_plate))) {
        $error = "Vehicle information is required for couriers";
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check_email->bind_param('s', $email);
        $check_email->execute();
        $check_email->store_result();
        
        if ($check_email->num_rows > 0) {
            $error = "Email already registered";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssss', $full_name, $email, $phone, $address, $hashed_password, $role);
                
                if (!$stmt->execute()) {
                    throw new Exception("User registration failed");
                }
                
                // Get the new user's ID
                $new_user_id = $stmt->insert_id;
                
                // If courier, insert courier-specific data
                if ($role == 'courier') {
                    $stmt = $conn->prepare("INSERT INTO couriers (user_id, vehicle_type, license_plate, status) VALUES (?, ?, ?, 'active')");
                    $stmt->bind_param('iss', $new_user_id, $vehicle_type, $license_plate);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Courier registration failed");
                    }
                }
                
                // Commit transaction
                $conn->commit();
                
                // Log in the user automatically
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_role'] = $role;
                
                // Set session cookie for longer persistence
                $sessionLifetime = 86400 * 30; // 30 days
                session_set_cookie_params($sessionLifetime);
                session_regenerate_id(true);
                
                // Redirect based on role
                if ($role == 'courier') {
                    header('Location: courier_dashboard.php');
                } else {
                    // For customers, check if there's a redirect URL from cart
                    if (isset($_SESSION['redirect_url'])) {
                        $redirect = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("Location: $redirect");
                    } else {
                        header('Location: index.php');
                    }
                }
                exit();
                
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Registration failed. Please try again. " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vasco Pharmacy</title>
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
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            overflow-y: auto;
            max-height: 90vh;
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
        
        .input-group input, .input-group select {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-group input:focus, .input-group select:focus {
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
        
        .success {
            color: #2ecc71;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .role-selection {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .role-selection h4 {
            margin-bottom: 10px;
            color: #4a5568;
        }
        
        .role-options {
            display: flex;
            gap: 15px;
        }
        
        .role-option {
            flex: 1;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .role-option:hover {
            border-color: #667eea;
        }
        
        .role-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .role-option input {
            display: none;
        }
        
        .role-option i {
            font-size: 24px;
            margin-bottom: 5px;
            color: #667eea;
        }
        
        .courier-fields {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-strength {
            margin-top: 5px;
            height: 5px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            background: #e74c3c;
            transition: width 0.3s;
        }
        
        .password-hint {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        
        @media (max-width: 600px) {
            .two-columns {
                grid-template-columns: 1fr;
            }
            
            .register-container {
                padding: 20px;
                max-width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="images/vasco.jpg" alt="Vasco Pharmacy">
            <h2>Create Account</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
            <div class="links">
                <a href="index.php">Click here if not redirected automatically</a>
            </div>
        <?php else: ?>
            <form action="register.php" method="POST" id="registrationForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="two-columns">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" id="address" name="address" placeholder="Your address" required
                                   value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Role Selection -->
                <div class="role-selection">
                    <h4>Register As:</h4>
                    <div class="role-options">
                        <label class="role-option selected" onclick="selectRole(this, 'customer')">
                            <input type="radio" name="role" value="customer" checked>
                            <i class="fas fa-user"></i>
                            <div>Customer</div>
                        </label>
                        
                        <label class="role-option" onclick="selectRole(this, 'courier')">
                            <input type="radio" name="role" value="courier">
                            <i class="fas fa-truck"></i>
                            <div>Courier</div>
                        </label>
                    </div>
                </div>
                
                <!-- Courier-specific fields -->
                <div id="courierFields" class="courier-fields">
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type</label>
                        <div class="input-group">
                            <i class="fas fa-car"></i>
                            <select id="vehicle_type" name="vehicle_type">
                                <option value="">Select Vehicle Type</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Car">Car</option>
                                <option value="Bicycle">Bicycle</option>
                                <option value="Van">Van</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="license_plate">License Plate</label>
                        <div class="input-group">
                            <i class="fas fa-id-card"></i>
                            <input type="text" id="license_plate" name="license_plate" placeholder="Vehicle license plate">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create password" required
                               oninput="checkPasswordStrength(this.value)">
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="password-hint" id="passwordHint">
                        Password must be at least 8 characters long
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required
                               oninput="checkPasswordMatch()">
                    </div>
                    <div class="password-hint" id="confirmPasswordHint"></div>
                </div>
                
                <button type="submit" class="btn" id="submitBtn">Register</button>
                
                <div class="links">
                    Already have an account? <a href="userlogin.php">Login here</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function selectRole(element, role) {
            // Remove selected class from all options
            document.querySelectorAll('.role-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Update the radio button
            document.querySelector(`input[value="${role}"]`).checked = true;
            
            // Show/hide courier fields
            const courierFields = document.getElementById('courierFields');
            if (role === 'courier') {
                courierFields.style.display = 'block';
                // Make courier fields required
                document.getElementById('vehicle_type').required = true;
                document.getElementById('license_plate').required = true;
            } else {
                courierFields.style.display = 'none';
                // Make courier fields not required
                document.getElementById('vehicle_type').required = false;
                document.getElementById('license_plate').required = false;
            }
        }
        
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrengthBar');
            const hint = document.getElementById('passwordHint');
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Update strength bar
            const width = strength * 20;
            strengthBar.style.width = width + '%';
            
            // Update colors and hint
            if (strength <= 1) {
                strengthBar.style.backgroundColor = '#e74c3c';
                hint.textContent = 'Weak password';
            } else if (strength <= 3) {
                strengthBar.style.backgroundColor = '#f39c12';
                hint.textContent = 'Moderate password';
            } else {
                strengthBar.style.backgroundColor = '#2ecc71';
                hint.textContent = 'Strong password';
            }
            
            checkPasswordMatch();
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const hint = document.getElementById('confirmPasswordHint');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword.length === 0) {
                hint.textContent = '';
                submitBtn.disabled = false;
                return;
            }
            
            if (password === confirmPassword) {
                hint.textContent = 'Passwords match!';
                hint.style.color = '#2ecc71';
                submitBtn.disabled = false;
            } else {
                hint.textContent = 'Passwords do not match';
                hint.style.color = '#e74c3c';
                submitBtn.disabled = true;
            }
        }
        
        // Initialize the form
        document.addEventListener('DOMContentLoaded', function() {
            // Check if courier was selected before form submission
            const roleInput = document.querySelector('input[name="role"]:checked');
            if (roleInput && roleInput.value === 'courier') {
                document.getElementById('courierFields').style.display = 'block';
            }
        });
    </script>
</body>
</html>