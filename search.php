<?php
session_start();
require 'db.php';

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT id, name, price, image FROM products WHERE name LIKE '%$query%' LIMIT 10");

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
    exit;
}

// Fetch all products for the search results page
$searchResults = [];
if (!empty($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT id, name, price, image FROM products WHERE name LIKE '%$query%'");

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Online Pharmacy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
        <img src="images/vasco.jpg" alt="Pharmacy Logo">
        <nav>
            <a href="index.php"><i class="fa fa-home"></i> Home</a>
            <a href="product.php"><i class="fa fa-shopping-cart"></i> Products</a>
            <a href="about.php"><i class="fa fa-info-circle"></i>About</a>
            <a href="contact.php"><i class="fa fa-envelope"></i>Contact</a>
            <a href="cart.php"><i class="fa fa-shopping-basket"></i>Cart</a>
            <form action="search.php" method="get" onsubmit="return vsearch()">
    <input type="text" name="query" id="search-input" placeholder="Search for products..." onkeyup="fetchSuggestions()">
    <button type="submit">Search</button>
    <div id="suggestions" class="suggestions"></div>
</form>

            <a href="my_orders.php" class="track-order-btn">Track Order</a>
        </nav>
    </header>
    <main>
        <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <div class="product-grid">
            <?php if (empty($searchResults)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <?php foreach ($searchResults as $product): ?>
                    <div class="product-item">
                        <img src="products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>$<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>">View Details</a>
                        <a href="cart.php?action=add&id=<?php echo $product['id']; ?>">Add to Cart</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <?php
   include('footer.php');
   ?>
    <script src="script.js"></script>
</body>
</html>
