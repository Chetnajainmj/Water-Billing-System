<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";  // your database username
$password = "root";      // your database password
$dbname = "water_billing";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest registration entry
$sql = "SELECT * FROM register ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

  
   // Get reading month (e.g., "May" or "5")
    $readingMonth = $row['reading_month'];
    
    // Convert the month name to a numeric value if necessary
    if (!is_numeric($readingMonth)) {
        $readingMonth = date('m', strtotime($readingMonth));
    }

    // Get current year
    $currentYear = date('Y');
    
    // Create a date string for the reading month
    $readingMonthDate = "$currentYear-$readingMonth-01";
    
    // Convert to timestamp
    $readingMonthTimestamp = strtotime($readingMonthDate);
    
    // Calculate the 15th of the next month
    $dueDate = date('Y-m-15', strtotime('+1 month', $readingMonthTimestamp));
    
    $amount = 2 * $row['meter_reading'];
} else {
    echo "No records found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="billpagestyle.css">
    <script>
        function printBill() {
            window.print();
        }
    </script>
</head>
<body>
    <h2>Bill Details</h2>
    <p>Name: <?php echo htmlspecialchars($row['fullname']); ?></p>
    <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
    <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($row['phone']); ?></p>
    <p>Reading Month: <?php echo htmlspecialchars($row['reading_month']); ?></p>
    <p>Meter Number: <?php echo htmlspecialchars($row['meter_number']); ?></p>
    <p>Connection Type: <?php echo htmlspecialchars($row['connection_type']); ?></p>
    <p>Meter Type: <?php echo htmlspecialchars($row['meter_type']); ?></p>
    <p>Meter Reading: <?php echo htmlspecialchars($row['meter_reading']); ?></p>
    <p>Amount Due: <?php echo htmlspecialchars($amount); ?></p>
    <p>Due Date: <?php echo htmlspecialchars($dueDate); ?></p>
    <button onclick="printBill()">Print Bill</button>
   
</body>
</html>