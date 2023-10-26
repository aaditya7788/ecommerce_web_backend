
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

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'product_ids' index exists in the $_POST array
    if (isset($_POST["product_ids"])) {
        // Get the list of product IDs to be removed
        $product_ids = $_POST["product_ids"];

        if (!empty($product_ids)) {
            // Convert the array of product IDs to a comma-separated string
            $product_ids_str = implode(',', $product_ids);

            // Delete the selected products from the database
            $sql = "DELETE FROM products WHERE product_id IN ($product_ids_str)";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Selected products removed successfully!";
            } else {
                $error_message = "Error removing products: " . $conn->error;
            }
        }
    }
}

// Retrieve the list of products from the database
$products = array(); // Initialize an empty array

// Query to fetch products from the database
$sql = "SELECT * FROM products"; // Replace with your actual table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row; // Add each product row to the array
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Remove Product</title>
</head>
<body>
    <header>
        <h1>Remove Product</h1>
    </header>
    <main>
        <?php
        if (isset($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        } elseif (isset($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
        <form method="post">
            <label>Select Products to Remove:</label>
            <?php
            foreach ($products as $product) {
                echo '<label>';
                echo '<input type="checkbox" name="product_ids[]" value="' . $product['product_id'] . '">';
                echo $product['product_name'];
                echo '</label>';
            }
            ?>
            <button type="submit">Remove Selected Products</button>
        </form>
    </main>
</body>
</html>
