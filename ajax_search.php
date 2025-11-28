<?php
require 'db.php';

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT * FROM products WHERE name LIKE '%$query%' LIMIT 5");

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    echo json_encode($suggestions);
}
?>
