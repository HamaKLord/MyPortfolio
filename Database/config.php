<?php
// Start the session
session_start();

// Database connection settings
$servername = "localhost";  // Use localhost or 127.0.0.1 for local MySQL connections
$username = "root";         // Default MySQL username for XAMPP / MySQL Workbench is 'root'
$password = "Hama1213h";    // Your MySQL password
$dbname = "car_equipment_shop";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Show error if connection fails
}

// Optionally, store the selected role in a variable for easy access
?>
