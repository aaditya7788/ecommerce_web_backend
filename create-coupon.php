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
    // Get form data
    $coupon_code = $_POST["coupon_code"];
    $discount_amount = floatval($_POST["discount_amount"]);
    $expiration_date = $_POST["expiration_date"];

    // Perform server-side validation here, such as checking for empty fields and valid data.

    if (!empty($coupon_code) && $discount_amount >= 1 && $discount_amount <= 100) {
        // If validation passes, insert the coupon into the database
        // Replace 'your_coupons_table' with your actual database table name
        $sql = "INSERT INTO your_coupons_table (coupon_code, discount_amount, expiration_date) VALUES (?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $coupon_code, $discount_amount, $expiration_date);

        if ($stmt->execute()) {
            $success_message = "Coupon created successfully!";
        } else {
            $error_message = "Error creating the coupon: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Please fill in all the fields with valid data.";
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
    <title>Create Coupon</title>
</head>
<body>
    <header>
        <h1>Create Coupon</h1>
    </header>
    <main>
        <?php
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        } elseif (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
        <form method="post">
            <label for="coupon_code">Coupon Code:</label>
            <input type="text" id="coupon_code" name="coupon_code" required>

            <label for="discount_amount">Discount Amount (%):</label>
            <input type="number" id="discount_amount" name="discount_amount" min="1" max="100" required>

            <label for="expiration_date">Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date" required>

            <button type="submit">Create Coupon</button>
        </form>
    </main>
</body>
</html>
