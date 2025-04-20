<?php
// Include database connection at the top
require_once 'phpProcesses/connection.php';

// Get sports categories
function getSportsCategories() {
    global $mysqli_conn;
    $sql = "SELECT id, name, slug, parent_id FROM categories WHERE name = 'Sports' OR parent_id = (SELECT id FROM categories WHERE name = 'Sports') ORDER BY name";
    $result = $mysqli_conn->query($sql);
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// Make sure getSubcategories is available - update to use mysqli_conn
if (!function_exists('getSubcategories')) {
    function getSubcategories($parentId) {
        global $mysqli_conn;
        $sql = "SELECT id, name, slug FROM categories WHERE parent_id = $parentId ORDER BY name";
        $result = $mysqli_conn->query($sql);
        $subcategories = [];
        
        while ($row = $result->fetch_assoc()) {
            $subcategories[] = $row;
        }
        
        return $subcategories;
    }
}
?>

<!-- Rest of your code remains the same -->

<!-- Rest of your HTML code -->

<div class="flex">
    <!-- Left sidebar -->
    <div class="w-64 bg-white p-4 shadow-md">
        <h3 class="text-lg font-bold mb-4">Categories</h3>
        
        <div class="sports-categories">
            <h4 class="font-bold mb-2">Sports</h4>
            <?php 
            $sportsCategories = getSportsCategories();
            $mainCategory = null;
            
            foreach ($sportsCategories as $category) {
                if ($category['name'] == 'Sports') {
                    $mainCategory = $category;
                    continue;
                }
                
                echo '<div class="ml-2 mb-1">';
                echo '<a href="category.php?slug=' . $category['slug'] . '" class="hover:text-red-600">' . $category['name'] . '</a>';
                
                $subcategories = getSubcategories($category['id']);
                if (!empty($subcategories)) {
                    echo '<div class="ml-4">';
                    foreach ($subcategories as $subcategory) {
                        echo '<div class="mb-1"><a href="category.php?slug=' . $subcategory['slug'] . '" class="text-sm hover:text-red-600">' . $subcategory['name'] . '</a></div>';
                    }
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
        
        <!-- Filter by Basketball attributes -->
        <div class="mt-6">
            <h3 class="text-lg font-bold mb-2">By Basketball</h3>
            
            <?php
            // Get basketball attributes
            $sql = "SELECT a.id, a.name, a.display_name 
                    FROM attributes a
                    JOIN product_attributes pa ON a.id = pa.attribute_id
                    JOIN products p ON pa.product_id = p.id
                    JOIN categories c ON p.category_id = c.id
                    WHERE c.name = 'Basketball'
                    GROUP BY a.id
                    ORDER BY a.display_name";
            $result = $conn->query($sql);
            
            while ($attr = $result->fetch_assoc()) {
                echo '<div class="mb-3">';
                echo '<h4 class="font-medium mb-1">By ' . $attr['display_name'] . '</h4>';
                
                // Get attribute values for this attribute
                $attrId = $attr['id'];
                $valuesSql = "SELECT DISTINCT pa.value 
                              FROM product_attributes pa
                              JOIN products p ON pa.product_id = p.id
                              JOIN categories c ON p.category_id = c.id
                              WHERE pa.attribute_id = $attrId AND c.name = 'Basketball'
                              ORDER BY pa.value";
                $valuesResult = $conn->query($valuesSql);
                
                while ($value = $valuesResult->fetch_assoc()) {
                    echo '<div class="flex items-center mb-1 ml-2">';
                    echo '<input type="checkbox" id="attr_' . $attrId . '_' . md5($value['value']) . '" class="mr-2">';
                    echo '<label for="attr_' . $attrId . '_' . md5($value['value']) . '">' . $value['value'] . '</label>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
        
        <!-- Search Filter -->
        <div class="mt-6">
            <h3 class="text-lg font-bold mb-2">Search Filter</h3>
            
            <div class="mb-4">
                <h4 class="font-medium mb-2">Price Range</h4>
                <div class="flex space-x-2">
                    <input type="text" placeholder="MIN" class="w-1/2 px-2 py-1 border rounded">
                    <input type="text" placeholder="MAX" class="w-1/2 px-2 py-1 border rounded">
                </div>
                <button class="mt-2 w-full bg-red-700 text-white py-1 uppercase">Apply</button>
            </div>
            
            <div class="mt-4">
                <h4 class="font-medium mb-2">Brands</h4>
                <?php
                // Get brands related to sports products
                $sql = "SELECT DISTINCT b.id, b.name FROM brands b 
                        JOIN products p ON p.brand_id = b.id 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE c.name = 'Sports' OR c.parent_id = (SELECT id FROM categories WHERE name = 'Sports')
                        ORDER BY b.name";
                $result = $conn->query($sql);
                
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="flex items-center mb-1">';
                    echo '<input type="checkbox" id="brand_' . $row['id'] . '" class="mr-2">';
                    echo '<label for="brand_' . $row['id'] . '">' . $row['name'] . '</label>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="flex-1 p-4">
        <!-- Breadcrumb navigation -->
        <div class="mb-4 text-sm">
            <a href="index.php" class="hover:text-red-600">Categories</a> / 
            <?php if (isset($_GET['subcategory'])): ?>
                <a href="category.php?slug=<?php echo $mainCategory['slug']; ?>" class="hover:text-red-600">Sports</a> / 
                <?php echo htmlspecialchars($_GET['subcategory']); ?>
            <?php else: ?>
                <span>Sports</span>
            <?php endif; ?>
        </div>
        
        <!-- Sort options -->
        <div class="flex justify-between mb-4">
            <div class="flex items-center">
                <span class="mr-2">Sort by</span>
                <button class="bg-red-700 text-white px-4 py-1 mr-2">Popular</button>
                <button class="bg-gray-200 px-4 py-1">New arrival</button>
            </div>
        </div>
        
        <!-- Products grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
            // Get products
            $categoryFilter = "";
            if (isset($_GET['slug'])) {
                $slug = $conn->real_escape_string($_GET['slug']);
                $categoryFilter = "AND c.slug = '$slug'";
            } else {
                $categoryFilter = "AND (c.name = 'Sports' OR c.parent_id = (SELECT id FROM categories WHERE name = 'Sports'))";
            }
            
            $sql = "SELECT p.id, p.name, p.price, p.slug, p.is_new_arrival, pi.image_url 
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                    WHERE 1=1 $categoryFilter
                    ORDER BY p.is_featured DESC, p.created_at DESC
                    LIMIT 12";
            $result = $conn->query($sql);
            
            while ($product = $result->fetch_assoc()) {
                ?>
                <div class="bg-white rounded shadow-sm">
                    <?php if ($product['is_new_arrival']): ?>
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</span>
                    </div>
                    <?php endif; ?>
                    
                    <a href="product.php?slug=<?php echo $product['slug']; ?>">
                        <img src="<?php echo $product['image_url'] ?? 'assets/placeholder.png'; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-contain p-4">
                    </a>
                    
                    <div class="p-4">
                        <h3 class="text-center mb-1">
                            <a href="product.php?slug=<?php echo $product['slug']; ?>" class="hover:text-red-600"><?php echo $product['name']; ?></a>
                        </h3>
                        <p class="text-center font-bold text-red-700">Php <?php echo number_format($product['price'], 2); ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            <div class="flex">
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-red-700 font-bold mr-2">1</a>
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full mr-2">2</a>
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full mr-2">3</a>
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full mr-2">4</a>
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full mr-2">5</a>
                <a href="#" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full bg-red-700 text-white">›</a>
            </div>
        </div>
    </div>
</div>