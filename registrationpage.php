<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $custid = $_POST['custid'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $meter_number = $_POST['meter_number'];
    $connection_type = $_POST['connection_type'];
    $meter_type = $_POST['meter_type'];
    $meter_reading = $_POST['meter_reading'];
    $reading_month = $_POST['reading_month'];
    if ($meter_reading < 1000) {
        $rewards = 500;
    } elseif ($meter_reading < 2000) {
        $rewards = 200;
    } elseif ($meter_reading < 3000) {
        $rewards = 100;
    } else {
        $rewards = 0;
    }

    // Database connection
    $servername = "localhost";
    $username_db = "root";
    $password_db = "root";
    $dbname = "water_billing";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get user ID from users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Prepare and bind SQL statement to insert into register table
    $stmt = $conn->prepare("INSERT INTO register (user_id,custid,fullname, email, address, phone, meter_number, connection_type, meter_type, meter_reading,reading_month,rewards) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("issssssssisi", $user_id,$custid, $fullname, $email, $address, $phone, $meter_number, $connection_type, $meter_type, $meter_reading,$reading_month,$rewards);

    if ($stmt->execute()) {
        echo "Registration successful.";
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>