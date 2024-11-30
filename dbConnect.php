<?php
// Database connection details
$dsn = 'mysql:host=localhost;dbname=lms'; // replace 'lms' with your actual database name
$username = 'root';
$password = ''; // replace with your MySQL password if needed

try {
    // Create a PDO connection
    $conn = new PDO($dsn, $username, $password);
    // Set the PDO error mode to exception for error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display a clear error message and stop execution if the connection fails
    die("Connection failed: " . $e->getMessage());
}
?>
