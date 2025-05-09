<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="Homepagestyle.css">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="Home3.png" class="logo" alt="Logo">
            <ul>
                
                <li><a href="billpage.php">Bills</a></li>
                <li><a href="aboutpage.html">About Us</a></li>
                <li><a href="registrationpage.html">Generate Bills</a></li>
                <li><a href="contactpage.php">Contact Us</a></li>
                <li><a href="savewaterpage.html">Save Water</a></li>
                <li><a href="graph.php">Analysis</a></li>
                <li><a href="rewards.php">Rewards</a></li>
               
            </ul>
        </div>
        <div class="content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Manage Water Bills</p>
        </div>
    </div>
</body>
</html>