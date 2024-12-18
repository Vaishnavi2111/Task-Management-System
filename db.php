<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_management";
$port = 3307;

// Create connection
$conn = new mysqli("localhost", "root", "", "task_management", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
