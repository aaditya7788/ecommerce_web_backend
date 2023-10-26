<?php
// Include your database connection code here, such as database credentials and connection setup.
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "store";

// Create a connection
$conn = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start or resume the user's session
session_start();

// Retrieve products from the database
// Replace 'your_products_table' with your actual database table name
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Initialize an array to store product data
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Handle "Add to Cart" action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Add the selected product to the shopping cart
    $_SESSION['cart'][] = $product_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home Page</title>
</head>
<body>
    <header>
        <h1>Welcome to Our E-commerce Store</h1>
    </header>
    <main>
        <?php
        foreach ($products as $product) {
            echo '<section class="product">';
            echo '<img src="' . $product['product_image'] . '" alt="' . $product['product_name'] . '">';
            echo '<h2>' . $product['product_name'] . '</h2>';
            echo '<p>$' . $product['product_price'] . '</p>';
            
            // Add to Cart form
            echo '<form method="post">';
            echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
            echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
            echo '</form>';
            
            echo '</section>';
        }
        ?>
    </main>
</body>
</html>
