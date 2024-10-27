<?php
// Database connection details
$host = 'localhost';
$db = 'calorietracker'; // Replace with your database name
$user = 'root'; // Default XAMPP username
$pass = ''; // Default XAMPP password is empty

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
