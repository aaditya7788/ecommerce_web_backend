<?php
// Include your database connection code here, such as database credentials and connection setup.
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "store"; // Replace with your actual database name

// Create a connection
$conn = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products"; // Replace 'products' with your actual table name
$result = $conn->query($sql);

// Initialize an array to store product data
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Clothing Brand</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        foreach ($products as $product) {
            echo '<section class="product">';
            echo '<img src="' . $product['product_image'] . '" alt="' . $product['product_name'] . '">';
            echo '<h2>' . $product['product_name'] . '</h2>';
            echo '<p>$' . $product['product_price'] . '</p>';
            echo '<form method="post" action="cart.php">';
            echo '<input type="hidden" name="product_name" value="' . $product['product_name'] . '">';
            echo '<input type="hidden" name="product_price" value="' . $product['product_price'] . '">';
            echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
            echo '</form>';
            echo '</section>';
        }
        ?>
    </main>
</body>
</html>
