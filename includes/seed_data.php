<?php
// Initialize database with sample categories
// db-init.php - Run once to set up categories
function initializeDatabase() {
    $conn = getConnection();
    
    // Check if categories already exist
    $stmt = $conn->query("SELECT COUNT(*) FROM categories");
    if ($stmt->fetchColumn() > 0) {
        return "Database already initialized";
    }
    
    // Main categories
    $mainCategories = [
        ['name' => 'Music', 'slug' => 'music', 'description' => 'Music instruments and equipment'],
        ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and accessories']
    ];
    
    $musicSubcategories = [
        ['name' => 'Acoustic Guitars', 'slug' => 'acoustic-guitars', 'description' => 'Acoustic guitars of all types'],
        ['name' => 'Electric Guitars', 'slug' => 'electric-guitars', 'description' => 'Electric guitars'],
        ['name' => 'Bass Guitars', 'slug' => 'bass-guitars', 'description' => 'Bass guitars'],
        ['name' => 'Ukuleles', 'slug' => 'ukuleles', 'description' => 'Ukuleles'],
        ['name' => 'Amplifiers', 'slug' => 'amplifiers', 'description' => 'Guitar and bass amplifiers']
    ];
    
    $sportsSubcategories = [
        ['name' => 'Basketball', 'slug' => 'basketball', 'description' => 'Basketball equipment'],
        ['name' => 'Volleyball', 'slug' => 'volleyball', 'description' => 'Volleyball equipment'],
        ['name' => 'Football', 'slug' => 'football', 'description' => 'Football equipment'],
        ['name' => 'Tennis', 'slug' => 'tennis', 'description' => 'Tennis equipment']
    ];
    
    $acousticSubcategories = [
        ['name' => 'Steel-String Acoustic', 'slug' => 'steel-string-acoustic', 'description' => 'Steel string acoustic guitars'],
        ['name' => 'Classical (Nylon-string)', 'slug' => 'classical-guitars', 'description' => 'Classical nylon string guitars'],
        ['name' => 'Acoustic-Electric', 'slug' => 'acoustic-electric', 'description' => 'Acoustic guitars with electronics'],
        ['name' => 'Resonator Guitar', 'slug' => 'resonator-guitar', 'description' => 'Resonator guitars'],
        ['name' => '12-String Acoustic', 'slug' => '12-string-acoustic', 'description' => '12-string acoustic guitars']
    ];
    
    $basketballSubcategories = [
        ['name' => 'Basketballs', 'slug' => 'basketballs', 'description' => 'Basketball balls'],
        ['name' => 'Basketball Hoops', 'slug' => 'basketball-hoops', 'description' => 'Basketball hoops and backboards'],
        ['name' => 'Basketball Accessories', 'slug' => 'basketball-accessories', 'description' => 'Accessories for basketball']
    ];
    
    $conn->beginTransaction();
    
    try {
        // Insert main categories
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        
        foreach ($mainCategories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description']]);
        }
        
        // Get music category ID
        $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmt->execute(['music']);
        $musicId = $stmt->fetchColumn();
        
        // Get sports category ID
        $stmt->execute(['sports']);
        $sportsId = $stmt->fetchColumn();
        
        // Insert subcategories
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, description, parent_id) VALUES (?, ?, ?, ?)");
        
        foreach ($musicSubcategories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description'], $musicId]);
        }
        
        foreach ($sportsSubcategories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description'], $sportsId]);
        }
        
        // Get acoustic guitars ID
        $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmt->execute(['acoustic-guitars']);
        $acousticId = $stmt->fetchColumn();
        
        // Get basketball ID
        $stmt->execute(['basketball']);
        $basketballId = $stmt->fetchColumn();
        
        // Insert third-level categories
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, description, parent_id) VALUES (?, ?, ?, ?)");
        
        foreach ($acousticSubcategories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description'], $acousticId]);
        }
        
        foreach ($basketballSubcategories as $cat) {
            $stmt->execute([$cat['name'], $cat['slug'], $cat['description'], $basketballId]);
        }
        
        // Sample brands
        $brands = [
            ['name' => 'Fender', 'slug' => 'fender'],
            ['name' => 'Gibson', 'slug' => 'gibson'],
            ['name' => 'Ibanez', 'slug' => 'ibanez'],
            ['name' => 'Yamaha', 'slug' => 'yamaha'],
            ['name' => 'Epiphone', 'slug' => 'epiphone'],
            ['name' => 'Martin', 'slug' => 'martin'],
            ['name' => 'Taylor', 'slug' => 'taylor'],
            ['name' => 'Spalding', 'slug' => 'spalding'],
            ['name' => 'Wilson', 'slug' => 'wilson'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Under Armour', 'slug' => 'under-armour'],
            ['name' => 'Adidas', 'slug' => 'adidas']
        ];
        
        $stmt = $conn->prepare("INSERT INTO brands (name, slug) VALUES (?, ?)");
        foreach ($brands as $brand) {
            $stmt->execute([$brand['name'], $brand['slug']]);
        }
        
        $conn->commit();
        
        return "Database initialized successfully";
    } catch (Exception $e) {
        $conn->rollBack();
        return "Error: " . $e->getMessage();
    }
}

// JavaScript for category navigation
// category.js
?>