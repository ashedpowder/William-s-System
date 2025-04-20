<?php
// Create a file called categoryHandler.php
if(isset($_GET['category'])) {
    $category = $_GET['category'];
    include 'phpProcesses/connection.php';
    include 'phpProcesses/categoryDisplay.php';
    echo displayCategoryProducts($category);
    exit;
}
?>