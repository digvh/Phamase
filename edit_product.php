<?php
session_start();

// Database connection
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$product_id = (int)$_GET['id'];
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_product'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = (float)$_POST['price'];
        $quantity = (int)$_POST['quantity'];
        $category = $conn->real_escape_string($_POST['category']);
        
        // Handle new category
        if ($category === 'new' && !empty($_POST['new_category'])) {
            $category = $conn->real_escape_string($_POST['new_category']);
            
            // Add new category to categories table if it doesn't exist
            $check_category = $conn->prepare("SELECT * FROM categore WHERE categories_name = ?");
            $check_category->bind_param('s', $category);
            $check_category->execute();
            $category_result = $check_category->get_result();
            
            if ($category_result->num_rows == 0) {
                $insert_category = $conn->prepare("INSERT INTO categore (categories_name) VALUES (?)");
                $insert_category->bind_param('s', $category);
                $insert_category->execute();
                $insert_category->close();
            }
            $check_category->close();
        }
        
        // Update product
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, category = ? WHERE id = ?");
        $stmt->bind_param('ssdisi', $name, $description, $price, $quantity, $category, $product_id);
        
        if ($stmt->execute()) {
            $success_message = 'Product updated successfully!';
        } else {
            $error_message = 'Error updating product: ' . $conn->error;
        }
        $stmt->close();
    }
    
    // Handle stock adjustment
    if (isset($_POST['adjust_stock'])) {
        $adjustment = (int)$_POST['stock_adjustment'];
        $adjustment_type = $_POST['adjustment_type'];
        $reason = $conn->real_escape_string($_POST['reason']);
        
        // Get current quantity
        $current_qty_query = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
        $current_qty_query->bind_param('i', $product_id);
        $current_qty_query->execute();
        $current_result = $current_qty_query->get_result();
        $current_product = $current_result->fetch_assoc();
        $current_qty = $current_product['quantity'];
        $current_qty_query->close();
        
        // Calculate new quantity
        if ($adjustment_type === 'add') {
            $new_qty = $current_qty + $adjustment;
        } else {
            $new_qty = $current_qty - $adjustment;
            if ($new_qty < 0) $new_qty = 0;
        }
        
        // Update product quantity
        $update_qty = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        $update_qty->bind_param('ii', $new_qty, $product_id);
        
        if ($update_qty->execute()) {
            // Log the stock adjustment (optional - you can create a stock_adjustments table)
            $success_message = 'Stock adjusted successfully! New quantity: ' . $new_qty;
        } else {
            $error_message = 'Error adjusting stock: ' . $conn->error;
        }
        $update_qty->close();
    }
}

// Fetch product details
$product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_query->bind_param('i', $product_id);
$product_query->execute();
$product_result = $product_query->get_result();

if ($product_result->num_rows == 0) {
    header('Location: admin_dashboard.php');
    exit();
}

$product = $product_result->fetch_assoc();
$product_query->close();

// Fetch categories
$categories_result = $conn->query("SELECT categories_name FROM categore");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['categories_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Pharmacy Admin</title>
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #1abc9c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --gray: #95a5a6;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        .breadcrumb {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            background: var(--gray);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #7f8c8d;
        }

        .back-btn svg {
            margin-right: 0.5rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .card h2 {
            margin-bottom: 1rem;
            color: var(--dark);
            font-size: 1.3rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--secondary);
            color: var(--white);
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-warning {
            background: var(--warning);
            color: var(--white);
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .product-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            padding: 1rem;
            background: var(--light);
            border-radius: 4px;
        }

        .info-item h4 {
            color: var(--gray);
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .info-item .value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .stock-status {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stock-high {
            background: #d4edda;
            color: #155724;
        }

        .stock-medium {
            background: #fff3cd;
            color: #856404;
        }

        .stock-low {
            background: #f8d7da;
            color: #721c24;
        }

        .adjustment-form {
            background: var(--light);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .product-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Edit Product</h1>
                <div class="breadcrumb">
                    <a href="admin_dashboard.php">Dashboard</a> / Products / Edit Product
                </div>
            </div>
            <a href="admin_dashboard.php" class="back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Main Edit Form -->
            <div>
                <div class="card">
                    <h2>Product Details</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="update_product" value="1">
                        
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price ($)</label>
                                <input type="number" id="price" name="price" class="form-control" 
                                       step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="quantity">Current Stock</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" 
                                       min="0" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>" 
                                            <?php echo ($category == $product['category']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="new">+ Add New Category</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="new-category-group" style="display: none;">
                            <label for="new_category">New Category Name</label>
                            <input type="text" id="new_category" name="new_category" class="form-control">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Product Info Card -->
                <div class="card">
                    <h2>Product Information</h2>
                    <div class="product-info">
                        <div class="info-item">
                            <h4>Product ID</h4>
                            <div class="value">#<?php echo htmlspecialchars($product['id']); ?></div>
                        </div>
                        <div class="info-item">
                            <h4>Current Price</h4>
                            <div class="value">$<?php echo number_format($product['price'], 2); ?></div>
                        </div>
                        <div class="info-item">
                            <h4>Stock Level</h4>
                            <div class="value">
                                <?php echo htmlspecialchars($product['quantity']); ?> units
                                <?php 
                                $stock_class = 'stock-high';
                                $stock_text = 'In Stock';
                                if ($product['quantity'] < 10) {
                                    $stock_class = 'stock-low';
                                    $stock_text = 'Low Stock';
                                } elseif ($product['quantity'] < 25) {
                                    $stock_class = 'stock-medium';
                                    $stock_text = 'Medium Stock';
                                }
                                ?>
                                <span class="stock-status <?php echo $stock_class; ?>"><?php echo $stock_text; ?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <h4>Category</h4>
                            <div class="value"><?php echo htmlspecialchars($product['category']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Stock Adjustment Card -->
                <div class="card">
                    <h2>Stock Adjustment</h2>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">
                        Quickly adjust inventory levels for this product.
                    </p>
                    
                    <div class="adjustment-form">
                        <form method="POST" action="">
                            <input type="hidden" name="adjust_stock" value="1">
                            
                            <div class="form-group">
                                <label>Adjustment Type</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="adjustment_type" value="add" checked>
                                        Add Stock
                                    </label>
                                    <label>
                                        <input type="radio" name="adjustment_type" value="subtract">
                                        Remove Stock
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock_adjustment">Quantity</label>
                                <input type="number" id="stock_adjustment" name="stock_adjustment" 
                                       class="form-control" min="1" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="reason">Reason (Optional)</label>
                                <input type="text" id="reason" name="reason" class="form-control" 
                                       placeholder="e.g., Restock, Damage, Sale">
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Adjust Stock</button>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <h2>Quick Actions</h2>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="view_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Product Details</a>
                        <a href="admin_dashboard.php" class="btn btn-success">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // New category selection
        document.getElementById('category').addEventListener('change', function() {
            const newCategoryGroup = document.getElementById('new-category-group');
            if (this.value === 'new') {
                newCategoryGroup.style.display = 'block';
                document.getElementById('new_category').required = true;
            } else {
                newCategoryGroup.style.display = 'none';
                document.getElementById('new_category').required = false;
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const price = parseFloat(document.getElementById('price').value);
            const quantity = parseInt(document.getElementById('quantity').value);
            
            if (price < 0) {
                alert('Price cannot be negative');
                e.preventDefault();
                return;
            }
            
            if (quantity < 0) {
                alert('Quantity cannot be negative');
                e.preventDefault();
                return;
            }
        });

        // Stock adjustment form validation
        document.querySelector('form[action=""]').addEventListener('submit', function(e) {
            if (this.querySelector('input[name="adjust_stock"]')) {
                const adjustment = parseInt(document.getElementById('stock_adjustment').value);
                const currentStock = <?php echo $product['quantity']; ?>;
                const isSubtract = document.querySelector('input[name="adjustment_type"]:checked').value === 'subtract';
                
                if (isSubtract && adjustment > currentStock) {
                    if (!confirm('This will reduce stock to 0. Are you sure you want to continue?')) {
                        e.preventDefault();
                        return;
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>