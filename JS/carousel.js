/**
 * Carousel Module
 * Handles image carousel functionality
 */
function initializeCarousel() {
    // DOM Elements
    const elements = {
        carouselGrid: document.querySelector('.carousel-grid'),
        prevButton: document.querySelector('.carousel-button.prev'),
        nextButton: document.querySelector('.carousel-button.next')
    };
    
    // Carousel state
    const carouselState = {
        currentPosition: 0,
        totalItems: 0,
        adjustedItemWidth: 0
    };
    
    // Initialize carousel and set up event listeners
    setup();
    
    /**
     * Sets up carousel and event listeners
     */
    function setup() {
        // Initialize carousel dimensions
        calculateDimensions();
        
        // Set up event listeners
        elements.prevButton.addEventListener('click', () => moveCarousel('prev'));
        elements.nextButton.addEventListener('click', () => moveCarousel('next'));
        
        // Handle window resize
        window.addEventListener('resize', () => {
            calculateDimensions();
        });
    }
    
    /**
     * Calculates carousel dimensions and item widths
     */
    function calculateDimensions() {
        const item = document.querySelector('.Item');
        if (!item) return;
        
        const itemWidth = item.offsetWidth + parseFloat(window.getComputedStyle(item).marginRight);
        
        const gapPercentage = 1;
        const containerWidth = elements.carouselGrid.offsetWidth;
        const gapInPixels = Math.floor((gapPercentage / 100) * containerWidth);
        
        carouselState.totalItems = document.querySelectorAll('.Item').length;
        carouselState.adjustedItemWidth = itemWidth + gapInPixels;
        
        carouselState.currentPosition = 0;
        elements.carouselGrid.style.transform = `translateX(${carouselState.currentPosition}px)`;
    }
    
    /**
     * Moves the carousel in the specified direction
     * @param {string} direction - Direction to move ('prev' or 'next')
     */
    function moveCarousel(direction) {
        const maxPosition = -(carouselState.adjustedItemWidth * (carouselState.totalItems - 4));
        
        if (direction === 'next') {
            carouselState.currentPosition -= carouselState.adjustedItemWidth;
            
            if (carouselState.currentPosition < maxPosition) {
                carouselState.currentPosition = 0;
            }
        } else if (direction === 'prev') {
            carouselState.currentPosition += carouselState.adjustedItemWidth;
            
            if (carouselState.currentPosition > 0) {
                carouselState.currentPosition = maxPosition;
            }
        }
        
        elements.carouselGrid.style.transform = `translateX(${carouselState.currentPosition}px)`;
    }
}