<?php
// Handle category display logic
function displayCategoryProducts($category) {
    global $conn;
    
    try {
        // Get main categories
        $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id IS NULL AND slug = :category");
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        $mainCategory = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$mainCategory) {
            return '<p class="text-center p-4">Category not found</p>';
        }
        
        // Get subcategories
        $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id = :parent_id");
        $stmt->bindParam(':parent_id', $mainCategory['id']);
        $stmt->execute();
        $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Start building output
        $output = '<div class="container mx-auto px-4 py-6">';
        $output .= '<div class="flex flex-wrap">';
        
        // Left sidebar with filters
        $output .= '<div class="w-full md:w-1/4 pr-4">';
        $output .= '<div class="bg-white rounded shadow p-4">';
        $output .= '<div class="mb-4">';
        $output .= '<h2 class="text-lg font-bold">Categories</h2>';
        $output .= '<div class="pl-2">';
        $output .= '<h3 class="font-bold mt-2">' . htmlspecialchars($mainCategory['name']) . '</h3>';
        
        // Display subcategories with collapsible sections
        foreach ($subcategories as $subcat) {
            $output .= '<div class="mt-2">';
            $output .= '<div class="flex items-center cursor-pointer subcategory-header" data-id="' . $subcat['id'] . '">';
            $output .= '<input type="checkbox" id="cat-' . $subcat['id'] . '" class="mr-2">';
            $output .= '<label for="cat-' . $subcat['id'] . '" class="cursor-pointer">' . htmlspecialchars($subcat['name']) . '</label>';
            $output .= '<span class="ml-2">&#8249;</span>';
            $output .= '</div>';
            
            // Get child categories
            $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id = :parent_id");
            $stmt->bindParam(':parent_id', $subcat['id']);
            $stmt->execute();
            $childCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Display child categories (initially hidden)
            $output .= '<div class="pl-6 mt-1 subcategory-items hidden" id="subcat-' . $subcat['id'] . '">';
            foreach ($childCategories as $child) {
                $output .= '<div class="my-1">';
                $output .= '<input type="checkbox" id="child-' . $child['id'] . '" class="mr-1">';
                $output .= '<label for="child-' . $child['id'] . '">' . htmlspecialchars($child['name']) . '</label>';
                $output .= '</div>';
            }
            $output .= '</div>'; // End subcategory-items
            $output .= '</div>'; // End subcategory container
        }
        
        $output .= '</div>'; // End categories pl-2 div
        $output .= '</div>'; // End categories mb-4 div
        
        // Price Range Filter
        $output .= '<div class="mb-4">';
        $output .= '<h2 class="text-lg font-bold">Price Range</h2>';
        $output .= '<div class="flex space-x-2 mt-2">';
        $output .= '<input type="number" placeholder="MIN" class="border p-2 w-1/2 rounded">';
        $output .= '<input type="number" placeholder="MAX" class="border p-2 w-1/2 rounded">';
        $output .= '</div>';
        $output .= '<button class="bg-red-800 text-white w-full py-2 px-4 rounded mt-2 font-bold">APPLY</button>';
        $output .= '</div>';
        
        // Brands Filter
        $output .= '<div class="mb-4">';
        $output .= '<h2 class="text-lg font-bold">Brands</h2>';
        
        // Get brands
        $stmt = $conn->prepare("
            SELECT DISTINCT b.* FROM brands b
            JOIN products p ON p.brand_id = b.id
            JOIN categories c ON p.category_id = c.id
            WHERE c.id = :category_id OR c.parent_id = :category_id
            OR c.parent_id IN (SELECT id FROM categories WHERE parent_id = :category_id)
        ");
        $stmt->bindParam(':category_id', $mainCategory['id']);
        $stmt->execute();
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($brands as $brand) {
            $output .= '<div class="my-1">';
            $output .= '<input type="checkbox" id="brand-' . $brand['id'] . '" class="mr-1">';
            $output .= '<label for="brand-' . $brand['id'] . '">' . htmlspecialchars($brand['name']) . '</label>';
            $output .= '</div>';
        }
        
        $output .= '</div>'; // End brands div
        $output .= '</div>'; // End sidebar bg-white div
        $output .= '</div>'; // End sidebar w-1/4 div
        
        // Right side with products
        $output .= '<div class="w-full md:w-3/4">';
        
        // Sort options
        $output .= '<div class="mb-4 bg-white p-4 rounded shadow">';
        $output .= '<div class="flex items-center">';
        $output .= '<span class="mr-2">Sort by</span>';
        $output .= '<button class="px-4 py-1 bg-red-800 text-white rounded mr-2">Popular</button>';
        $output .= '<button class="px-4 py-1 bg-white border border-gray-300 rounded">New arrival</button>';
        $output .= '</div>';
        $output .= '</div>';
        
        // Products Grid
        $output .= '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">';
        
        // Get products
        $stmt = $conn->prepare("
            SELECT p.*, pi.image_url FROM products p
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE p.category_id IN (
                SELECT id FROM categories WHERE 
                id = :category_id OR parent_id = :category_id OR
                parent_id IN (SELECT id FROM categories WHERE parent_id = :category_id)
            )
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT 20
        ");
        $stmt->bindParam(':category_id', $mainCategory['id']);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as $product) {
            $output .= '<div class="bg-white rounded shadow overflow-hidden">';
            
            // Sale tag
            if ($product['is_on_sale']) {
                $output .= '<div class="absolute bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center -mt-2 -ml-2">Sale</div>';
            }
            
            // Product image
            $imageUrl = $product['image_url'] ? $product['image_url'] : 'assets/placeholder.jpg';
            $output .= '<div class="p-4">';
            $output .= '<img class="w-full h-40 object-contain" src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '">';
            $output .= '</div>';
            
            // Product details
            $output .= '<div class="p-4 border-t">';
            $output .= '<h3 class="font-bold text-sm mb-1">' . htmlspecialchars($product['name']) . '</h3>';
            $output .= '<p class="text-sm">Php ' . number_format($product['price'], 2) . '</p>';
            $output .= '</div>';
            
            $output .= '</div>'; // End product card
        }
        
        // Pagination
        if (count($products) > 0) {
            $output .= '<div class="col-span-full flex justify-center mt-6">';
            $output .= '<div class="flex space-x-1">';
            $output .= '<a href="#" class="bg-red-800 text-white w-8 h-8 rounded-full flex items-center justify-center">1</a>';
            $output .= '<a href="#" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">2</a>';
            $output .= '<a href="#" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">3</a>';
            $output .= '<a href="#" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">4</a>';
            $output .= '<a href="#" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">5</a>';
            $output .= '<a href="#" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">&gt;</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>'; // End products grid
        $output .= '</div>'; // End right side w-3/4 div
        $output .= '</div>'; // End flex-wrap
        $output .= '</div>'; // End container
        
        return $output;
    } catch (PDOException $e) {
        return '<p class="text-red-500 p-4">Database error: ' . $e->getMessage() . '</p>';
    }
}
?>