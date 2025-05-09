<?php
// Include database connection
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

// Fetch data for each month (January to June)
$monthsData = [];
$monthLabels = [];
for ($i = 1; $i <= date('m'); $i++) { // Assuming current month is June (month number 6)
    $sql = "SELECT sum(meter_reading) FROM register WHERE reading_month= $i";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $monthsData[] = isset($row['sum(meter_reading)']) ? $row['sum(meter_reading)'] : 0;
    $monthLabels[] = date('F', mktime(0, 0, 0, $i, 1)); // Month name
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Water Usage Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <canvas id="usageChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('usageChart').getContext('2d');
        var usageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthLabels); ?>,
                datasets: [{
                    label: 'Meter Reading',
                    data: <?php echo json_encode($monthsData); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>