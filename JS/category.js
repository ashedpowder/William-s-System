// category.js
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    const dynamicContent = document.getElementById('dynamicContent');
    
    // Add click event listeners to the category items
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            loadCategory(category);
        });
    });
    
    function loadCategory(category) {
        // Update URL to reflect current category
        history.pushState({category: category}, '', '?category=' + category);
        
        // Fetch the category content
        fetch('categoryHandler.php?category=' + category)
            .then(response => response.text())
            .then(data => {
                dynamicContent.innerHTML = data;
                
                // Setup subcategory toggles after content is loaded
                setupSubcategoryToggles();
            })
            .catch(error => {
                console.error('Error loading category:', error);
                dynamicContent.innerHTML = '<p class="text-red-500 p-4">Error loading category content.</p>';
            });
    }
    
    function setupSubcategoryToggles() {
        const subcategoryHeaders = document.querySelectorAll('.subcategory-header');
        
        subcategoryHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const items = document.getElementById('subcat-' + id);
                
                // Toggle visibility of subcategory items
                if (items.classList.contains('hidden')) {
                    items.classList.remove('hidden');
                    this.querySelector('span').innerHTML = '&#8250;'; // Change arrow direction
                } else {
                    items.classList.add('hidden');
                    this.querySelector('span').innerHTML = '&#8249;';
                }
            });
        });
    }
    
    // Handle browser back/forward navigation
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.category) {
            loadCategory(event.state.category);
        } else {
            // Load default content if no state
            dynamicContent.innerHTML = '<?php include "phpComponents/landing.php"; ?>';
        }
    });
    
    // Check if we need to load a category on page load (from URL parameter)
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    
    if (categoryParam) {
        loadCategory(categoryParam);
    }
});