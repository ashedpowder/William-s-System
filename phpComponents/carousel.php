<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="\css\index.css">
</head>
<body>
<h2 class="font-bold text-3xl mb-4" >Most popular</h2>
    <hr>
    <div class="carousel">
        <button class="carousel-button prev">&#10094;</button>
        <div class="carousel-grid">
            <?php
            $carouselItems = getCarouselItems();
            if (count($carouselItems) > 0) {
                foreach ($carouselItems as $item) {
                    ?>
                    <div class="Item">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <p class="name"><?php echo htmlspecialchars($item['name']); ?></p>
                        <p class="price"><?php echo formatPrice($item['price']); ?></p>
                    </div>
                    <?php
                }
            } else {
                // Fallback if no items found
                ?>
                <div class="Item">
                    <img src="assets/no-image.png" alt="No items available">
                    <p class="name">No items available</p>
                    <p class="price">-</p>
                </div>
                <?php
            }
            ?>
        </div>
        <button class="carousel-button next">&#10095;</button>
    </div>
</body>
</html>