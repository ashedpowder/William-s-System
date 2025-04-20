<?php
// This file handles the category navigation and can be included in the index.php

// Get main categories
function getMainCategories() {
    global $conn;
    $sql = "SELECT id, name, slug FROM categories WHERE parent_id IS NULL ORDER BY name";
    $result = $conn->query($sql);
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// AJAX handler for loading category content
if (isset($_GET['load_category'])) {
    $category = $_GET['load_category'];
    
    if ($category == 'music') {
        include 'categories_music.php';
    } elseif ($category == 'sports') {
        include 'categories_sports.php';
    }
    
    exit;
}
?>