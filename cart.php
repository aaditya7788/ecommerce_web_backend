<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];

    // Add the selected product to the shopping cart
    $_SESSION['cart'][] = [
        'name' => $product_name,
        'price' => $product_price,
    ];
}


// Remove a product from the cart if the "Remove" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $remove_index = $_POST["remove_index"];
    if (isset($_SESSION['cart'][$remove_index])) {
        unset($_SESSION['cart'][$remove_index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cart</title>
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
        <h2>Shopping Cart</h2>
        <ul>
            <?php
            $total_price = 0;
            foreach ($_SESSION['cart'] as $index => $product) {
                echo '<li>';
                echo '<span>' . $product['name'] . ' - $' . $product['price'] . '</span>';
                echo '<form method="post" action="cart.php">';
                echo '<input type="hidden" name="remove_index" value="' . $index . '">';
                echo '<button type="submit" name="remove_from_cart">Remove</button>';
                echo '</form>';
                echo '</li>';
                $total_price += $product['price'];
            }
            ?>
        </ul>
        <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>
    </main>
</body>
</html>
