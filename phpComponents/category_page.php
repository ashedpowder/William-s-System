<?php
// Place this in a new file: phpComponents/category_page.php
include_once 'phpProcesses/categories_handler.php';

$category_type = $_GET['type'] ?? '';
$selected_category = $_GET['category'] ?? '';
$sort_by = $_GET['sort'] ?? 'popular';

if (!in_array($category_type, ['Music', 'Sports'])) {
    echo "Invalid category type";
    exit;
}

$categories = getCategoriesByType($category_type, $conn);
$brands = getBrands($conn);
?>

<div class="container mx-auto">
    <div class="text-sm breadcrumbs px-4 py-2">
        <span>Categories / </span>
        <span><?php echo htmlspecialchars($category_type); ?></span>
        <?php if ($selected_category): ?>
            <span> / <?php echo htmlspecialchars($selected_category); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="flex flex-col md:flex-row">
        <!-- Left sidebar for categories -->
        <div class="md:w-1/4 bg-white p-4 border-r">
            <div class="categories-sidebar">
                <h2 class="font-bold text-xl"><?php echo htmlspecialchars($category_type); ?></h2>
                
                <?php foreach ($categories as $category): ?>
                    <div class="category-group my-2">
                        <div class="flex items-center">
                            <input type="radio" name="category" id="cat_<?php echo $category['id']; ?>" 
                                class="category-checkbox" data-id="<?php echo $category['id']; ?>"
                                <?php echo ($selected_category == $category['slug']) ? 'checked' : ''; ?>>
                            <label for="cat_<?php echo $category['id']; ?>" class="ml-2 font-medium">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </label>
                            <?php if ($category['has_children'] > 0): ?>
                                <span class="arrow ml-auto">&#8249;</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($category['has_children'] > 0): ?>
                            <div class="subcategories ml-6 hidden">
                                <?php 
                                $subcategories = getSubcategories($category['id'], $conn);
                                foreach ($subcategories as $subcategory): 
                                ?>
                                    <div class="flex items-center my-1">
                                        <input type="radio" name="subcategory" id="subcat_<?php echo $subcategory['id']; ?>"
                                            class="subcategory-checkbox" data-id="<?php echo $subcategory['id']; ?>">
                                        <label for="subcat_<?php echo $subcategory['id']; ?>" class="ml-2">
                                            <?php echo htmlspecialchars($subcategory['name']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <div class="mt-6">
                    <h3 class="font-bold">Search Filter</h3>
                    <div class="mt-4">
                        <h4 class="font-medium">Price Range</h4>
                        <div class="flex items-center mt-2">
                            <input type="number" placeholder="MIN" class="w-16 h-8 border px-2 text-sm">
                            <span class="mx-2">-</span>
                            <input type="number" placeholder="MAX" class="w-16 h-8 border px-2 text-sm">
                        </div>
                        <button class="bg-red-800 text-white w-full py-1 mt-4">APPLY</button>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="font-bold">Brands</h3>
                    <div class="mt-2">
                        <?php foreach ($brands as $brand): ?>
                            <div class="flex items-center my-1">
                                <input type="checkbox" id="brand_<?php echo $brand['id']; ?>" 
                                    class="brand-checkbox" data-id="<?php echo $brand['id']; ?>">
                                <label for="brand_<?php echo $brand['id']; ?>" class="ml-2">
                                    <?php echo htmlspecialchars($brand['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right content area for products -->
        <div class="md:w-3/4 bg-white p-4">
            <!-- Sorting options -->
            <div class="flex justify-end mb-4">
                <span class="mr-2">Sort by</span>
                <div class="flex space-x-2">
                    <a href="?type=<?php echo urlencode($category_type); ?>&sort=popular" 
                       class="px-4 py-1 <?php echo ($sort_by == 'popular') ? 'bg-red-800 text-white' : 'bg-gray-200'; ?>">
                        Popular
                    </a>
                    <a href="?type=<?php echo urlencode($category_type); ?>&sort=new_arrival" 
                       class="px-4 py-1 <?php echo ($sort_by == 'new_arrival') ? 'bg-red-800 text-white' : 'bg-gray-200'; ?>">
                        New arrival
                    </a>
                </div>
            </div>
            
            <!-- Products grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 product-container">
                <?php
                // If a category is selected, fetch and display products
                if ($selected_category):
                    // Find the category ID from slug
                    $category_id = null;
                    foreach ($categories as $category) {
                        if ($category['slug'] == $selected_category) {
                            $category_id = $category['id'];
                            break;
                        }
                    }
                    
                    if ($category_id):
                        $products = getProductsByCategory($category_id, $sort_by, $conn);
                        foreach ($products as $product):
                ?>
                <div class="product-card border rounded-lg overflow-hidden">
                    <div class="relative">
                        <img src="<?php echo $product['image_url'] ?? 'assets/placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             class="w-full h-48 object-cover">
                        <?php if ($product['is_on_sale']): ?>
                            <div class="absolute top-0 right-0 bg-red-600 text-white text-xs px-2 py-1 rounded-bl">SALE</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3">
                        <h4 class="font-medium"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <div class="mt-1">
                            <?php if ($product['sale_price']): ?>
                                <span class="line-through text-gray-500">Php <?php echo number_format($product['price'], 2); ?></span>
                                <span class="font-bold">Php <?php echo number_format($product['sale_price'], 2); ?></span>
                            <?php else: ?>
                                <span class="font-bold">Php <?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                        endforeach;
                        if (empty($products)):
                ?>
                    <div class="col-span-full text-center py-10">
                        <p>No products found in this category.</p>
                    </div>
                <?php 
                        endif;
                    endif;
                endif;
                ?>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center mt-8">
                <div class="flex space-x-1">
                    <a href="#" class="bg-red-800 text-white px-3 py-1 rounded-full">1</a>
                    <a href="#" class="px-3 py-1 rounded-full">2</a>
                    <a href="#" class="px-3 py-1 rounded-full">3</a>
                    <a href="#" class="px-3 py-1 rounded-full">4</a>
                    <a href="#" class="px-3 py-1 rounded-full">5</a>
                    <a href="#" class="px-3 py-1 rounded-full">›</a>
                </div>
            </div>
        </div>
    </div>
</div>