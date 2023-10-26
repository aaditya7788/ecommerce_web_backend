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
    // Get and sanitize form data
    $product_name = htmlspecialchars($_POST["product_name"]);
    $product_description = htmlspecialchars($_POST["product_description"]);
    $product_price = floatval($_POST["product_price"]);

    // Handle image upload
    $uploadDir = "uploads/"; // Create a directory to store uploaded images
    $uploadedImage = $uploadDir . basename($_FILES["product_image"]["name"]);

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $uploadedImage)) {
        // Image upload successful
        $imagePath = $uploadedImage;

        // Perform server-side validation here, such as checking for empty fields and valid data.
        if (!empty($product_name) && !empty($product_description) && $product_price > 0) {
            // If validation passes, insert the product into the database
            $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES (?, ?, ?, ?)";

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssds", $product_name, $product_description, $product_price, $imagePath);

            if ($stmt->execute()) {
                $success_message = "Product added successfully!";
                echo "<meta http-equiv='refresh' content='3; url=admin-pannel.html'>";
            } else {
                $error_message = "Error adding the product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Please fill in all the fields with valid data.";
        }
    } else {
        $error_message = "Error uploading the image.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... your head content ... -->
</head>
<body>
    <header>
        <h1>Add Product</h1>
    </header>
    <main>
        <?php
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        } elseif (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description" required></textarea>

            <label for="product_price">Product Price:</label>
            <input type="number" id="product_price" name="product_price" step="0.01" required>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required>

            <button type="submit">Add Product</button>
        </form>
    </main>
</body>
</html>
