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

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform server-side validation here, if needed.

    // Check if the provided username is valid
    $sql = "SELECT username, password FROM users WHERE username = ?";
    
    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($dbUsername, $hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if ($dbUsername && password_verify($password, $hashedPassword)) {
            // Login successful
            session_start();
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
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
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <?php
        if (!empty($login_error)) {
            echo '<div class="error">' . $login_error . '</div>';
        }
        ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
