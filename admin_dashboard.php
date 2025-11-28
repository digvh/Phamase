<?php
session_start();

// Database connection
include('db.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle order cancellation
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['order_id'])) {
    $orderId = (int)$_GET['order_id'];
    $newStatus = $_GET['status'];
    
    // Include the status update logic from admin_update_order_status.php
    require 'admin_update_order_status.php';
    // Then redirect back
    header('Location: admin_dashboard.php');
    exit();
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = ''; // Add this field to your form if needed
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $category = $conn->real_escape_string($_POST['category']);
    $image = 'default.jpg'; // Set a default image
    
    $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sdiss', $name, $price, $quantity, $category, $image);
    $stmt->execute();
    $stmt->close();
    
    header('Location: admin_dashboard.php');
    exit();
}

// Fetch orders
$order_query = "
    SELECT 
        orders.id as order_id, 
        orders.first_name, 
        orders.last_name, 
        orders.order_date, 
        orders.order_status,
        orders.email,
        orders.phone,
        SUM(order_items.quantity * order_items.price) AS total_price
    FROM orders
    LEFT JOIN order_items ON orders.id = order_items.order_id
    GROUP BY orders.id, orders.first_name, orders.last_name, orders.order_date, orders.order_status
    ORDER BY orders.order_date DESC
";
$order_result = $conn->query($order_query);

// Fetch product categories from your categore table
$categories_result = $conn->query("SELECT categories_name FROM categore");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['categories_name'];
}

// Fetch low stock products (if quantity column exists)
$low_stock_query = "SELECT * FROM products WHERE quantity < 10 ORDER BY quantity ASC LIMIT 5";
$low_stock_result = $conn->query($low_stock_query);

// Get order statistics
$stats_query = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN order_status = 'Completed' THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN order_status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
    SUM(CASE WHEN order_status = 'Processing' THEN 1 ELSE 0 END) as processing_orders,
    SUM(CASE WHEN order_status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders
    FROM orders";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get recent products
$recent_products_query = "SELECT * FROM products ORDER BY id DESC LIMIT 5";
$recent_products_result = $conn->query($recent_products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Pharmacy Management</title>
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

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--dark);
            color: var(--white);
            padding: 1.5rem 0;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
        }

        .sidebar-header h2 svg {
            margin-right: 0.5rem;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .sidebar-menu h3 {
            padding: 0 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li a {
            display: block;
            padding: 0.8rem 1.5rem;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu li a.active {
            background: var(--primary);
        }

        .sidebar-menu li a svg {
            margin-right: 0.5rem;
            width: 1.2rem;
            height: 1.2rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 1.5rem;
            background: #f5f7fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .logout-btn {
            background: var(--danger);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-header h3 {
            font-size: 1rem;
            color: var(--gray);
        }

        .card-header .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .card.orders .icon {
            background: rgba(52, 152, 219, 0.1);
            color: var(--primary);
        }

        .card.sales .icon {
            background: rgba(46, 204, 113, 0.1);
            color: var(--secondary);
        }

        .card.pending .icon {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .card.products .icon {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-footer {
            font-size: 0.8rem;
            color: var(--gray);
        }

        /* Tables */
        .table-container {
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .table-header h2 {
            font-size: 1.3rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: var(--light);
            font-weight: 600;
            color: var(--dark);
        }

        tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }

        .status {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8rem;
            margin-right: 0.3rem;
            display: inline-block;
        }

        .view-btn {
            background: var(--primary);
            color: var(--white);
        }

        .cancel-btn {
            background: var(--danger);
            color: var(--white);
        }

        .edit-btn {
            background: var(--warning);
            color: var(--white);
        }

        /* Forms */
        .form-container {
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }
/* Quick Actions Styles */
.quick-actions {
    display: inline-flex;
    gap: 0.3rem;
    margin-left: 0.5rem;
}

.action-btn.process-btn {
    background: var(--warning);
    color: white;
}

.action-btn.ship-btn {
    background: var(--info);
    color: white;
}

.action-btn.cancel-btn {
    background: var(--danger);
    color: white;
}

.action-btn.process-btn:hover {
    background: #d68910;
}

.action-btn.ship-btn:hover {
    background: #17a2b8;
}
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.5rem;
        }

        .tab {
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.5 0a.5.5 0 0 1 .5.5V1h6V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5V6H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V7H6a.5.5 0 0 1 0-1h1.5V4.5A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Pharmacy Admin
                </h2>
            </div>

            <div class="sidebar-menu">
                <h3>Main</h3>
                <ul>
                    <li><a href="#" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6z"/>
                        </svg>
                        Dashboard
                    </a></li>
                </ul>

                <h3>Management</h3>
                <ul>
                    <li><a href="#orders">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        Orders
                    </a></li>
                    <li><a href="#products">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                        </svg>
                        Products
                    </a></li>
                    <li><a href="#inventory">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.793L5.354 7.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 9.293V5.5z"/>
                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                        </svg>
                        Inventory
                    </a></li>
                </ul>

                <h3>Settings</h3>
                <ul>
                    <li><a href="#settings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                        </svg>
                        Settings
                    </a></li>
                    <li><a href="logout.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Logout
                    </a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_full_name']); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card orders">
                    <div class="card-header">
                        <h3>Total Orders</h3>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $stats['total_orders']; ?></div>
                    <div class="card-footer">All time orders</div>
                </div>

                <div class="card sales">
                    <div class="card-header">
                        <h3>Completed Orders</h3>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $stats['completed_orders']; ?></div>
                    <div class="card-footer">Successfully delivered</div>
                </div>

                <div class="card pending">
                    <div class="card-header">
                        <h3>Pending Orders</h3>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $stats['pending_orders']; ?></div>
                    <div class="card-footer">Awaiting processing</div>
                </div>

                <div class="card products">
                    <div class="card-header">
                        <h3>Low Stock</h3>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $low_stock_result->num_rows; ?></div>
                    <div class="card-footer">Products need restocking</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" data-tab="orders">Orders</div>
                <div class="tab" data-tab="products">Products</div>
                <div class="tab" data-tab="inventory">Inventory</div>
            </div>

            <!-- Orders Tab -->
            <div class="tab-content active" id="orders">
                <div class="table-container">
                    <div class="table-header">
                        <h2>Recent Orders</h2>
                    </div>
                    
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($order = $order_result->fetch_assoc()) { ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                        <div class="text-muted"><?php echo htmlspecialchars($order['email']); ?></div>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                    <td>
                                        <span class="status status-<?php echo strtolower($order['order_status']); ?>">
                                            <?php echo htmlspecialchars($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td>
    <!-- View Button (unchanged) -->
    <a href="view_order.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" 
       class="action-btn view-btn">View</a>
    
    <!-- Quick Status Actions -->
    <?php if ($order['order_status'] != 'Cancelled' && $order['order_status'] != 'Completed'): ?>
        <div class="quick-actions">
            <?php if ($order['order_status'] == 'Pending'): ?>
                <a href="admin_update_order_status.php?order_id=<?php echo $order['order_id']; ?>&status=Processing"
                   class="action-btn process-btn"
                   title="Mark as Processing"
                   onclick="return confirm('Mark order #<?php echo $order['order_id']; ?> as Processing?')">
                    Process
                </a>
            <?php endif; ?>
            
            <?php if ($order['order_status'] == 'Processing'): ?>
                <a href="admin_update_order_status.php?order_id=<?php echo $order['order_id']; ?>&status=Shipped"
                   class="action-btn ship-btn"
                   title="Mark as Shipped"
                   onclick="return confirm('Mark order #<?php echo $order['order_id']; ?> as Shipped?')">
                    Ship
                </a>
            <?php endif; ?>
            
            <a href="admin_update_order_status.php?order_id=<?php echo $order['order_id']; ?>&status=Cancelled"
               class="action-btn cancel-btn"
               title="Cancel Order"
               onclick="return confirm('Cancel order #<?php echo $order['order_id']; ?>?')">
                Cancel
            </a>
        </div>
    <?php endif; ?>
</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Tab -->
            <div class="tab-content" id="products">
                <div class="table-container">
                    <div class="table-header">
                        <h2>Recent Products</h2>
                    </div>
                    
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($product = $recent_products_result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="action-btn edit-btn">Edit</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-container">
                    <h2>Add New Product</h2>
                    <form method="POST" action="admin_dashboard.php">
                        <input type="hidden" name="add_product" value="1">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                <?php endforeach; ?>
                                <option value="new">+ Add New Category</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="new-category-group" style="display: none;">
                            <label for="new_category">New Category Name</label>
                            <input type="text" id="new_category" name="new_category" class="form-control">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>

            <!-- Inventory Tab -->
            <div class="tab-content" id="inventory">
                <div class="table-container">
                    <div class="table-header">
                        <h2>Low Stock Items</h2>
                    </div>
                    
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Current Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($product = $low_stock_result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <span class="status status-pending">Low Stock</span>
                                    </td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="action-btn edit-btn">Restock</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // New category selection
        document.getElementById('category').addEventListener('change', function() {
            const newCategoryGroup = document.getElementById('new-category-group');
            if (this.value === 'new') {
                newCategoryGroup.style.display = 'block';
            } else {
                newCategoryGroup.style.display = 'none';
            }
        });
        // Add to your existing <script> section
function quickUpdateStatus(orderId, status) {
    if (!confirm(`Change order #${orderId} to ${status}?`)) return;
    
    fetch(`admin_update_order_status.php?order_id=${orderId}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh to show new status
            } else {
                alert('Error: ' + data.message);
            }
        });
}
    </script>
</body>
</html>

<?php
$conn->close();
?>