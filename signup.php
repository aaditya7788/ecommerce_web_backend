<?php
// Include your database connection code here
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
    // Retrieve user registration data from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    // You can add more fields as needed

    // Perform server-side validation here, such as checking for empty fields and valid data.

    // Check if the username or email is already registered
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $error_message = "Username or email is already registered.";
    } else {
        // If validation passes and the username or email is not already registered, insert the new user into the database
        $insert_query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        
        // Use prepared statements to prevent SQL injection
        if ($stmt = $conn->prepare($insert_query)) {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt->bind_param("sss", $username, $hashed_password, $email);

            if ($stmt->execute()) {
                $success_message = "Registration successful! You can now login.";
            } else {
                $error_message = "Error registering the user: " . $stmt->error;
            }

            $stmt->close();
        }
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
    <title>Signup</title>
</head>
<body>
    <header>
        <h1>Signup</h1>
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
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </main>
</body>
</html>
