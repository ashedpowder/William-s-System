<?php
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '#071823Kl';

// Create PDO connection for prepared statements
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create MySQLi connection for the category files
$mysqli_conn = new mysqli($host, $username, $password, $dbname);
if ($mysqli_conn->connect_error) {
    die("MySQLi connection failed: " . $mysqli_conn->connect_error);
}

// Update categories_music.php and categories_sports.php to use $mysqli_conn
global $mysqli_conn;
?>