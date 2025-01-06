<?php
$servername = "sql109.infinityfree.com"; // Change if your server isn't localhost
$username = "if0_38049630"; // Your MySQL username
$password = "Horoscope123"; // Your MySQL password
$dbname = "if0_38049630_horoscope_website"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
