<?php
$host = 'localhost';      // Database server
$user = 'root';  // Database username
$pass = '';  // Database password
$db   = 'healthhub';  // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for proper encoding support
$conn->set_charset("utf8mb4");
