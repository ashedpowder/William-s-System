<?php

$host = 'localhost';
$dbname = 'carousel';
$username = 'root';
$password = '#071823Kl';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


function getCarouselItems() {
    global $conn;
    
    // Prepare query to fetch items
    $query = "SELECT id, name, price, image_url FROM item ORDER BY id DESC LIMIT 10";
    $statement = $conn->prepare($query);
    $statement->execute();
    
    $items = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $items;
}

// Function to format price with currency symbol
function formatPrice($price) {
    return '₱' . number_format($price, 2);
}