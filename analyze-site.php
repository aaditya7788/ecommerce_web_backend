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

// Initialize variables to store statistics
$total_sales = 0;
$total_visitors = 0;
$average_order_value = 0;

// Fetch and calculate site statistics from the 'site_statistics' table
$sql = "SELECT * FROM site_statistics";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_sales += $row["sales"];
        $total_visitors += $row["visitors"];
    }

    // Calculate average order value
    if ($total_visitors > 0) {
        $average_order_value = $total_sales / $total_visitors;
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
    <title>Analyze Site</title>
</head>
<body>
    <header>
        <h1>Analyze Site</h1>
    </header>
    <main>
        <h2>Site Statistics</h2>
        <p>Total Sales: $<?php echo $total_sales; ?></p>
        <p>Total Visitors: <?php echo $total_visitors; ?></p>
        <p>Average Order Value: $<?php echo $average_order_value; ?></p>
    </main>
</body>
</html>
