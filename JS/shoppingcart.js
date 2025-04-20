      // Select All functionality
      const selectAllCheckbox = document.querySelector('.cart-header input[type="checkbox"]');
      const itemCheckboxes = document.querySelectorAll('.cart-item input[type="checkbox"]');
    
      selectAllCheckbox.addEventListener('change', () => {
          itemCheckboxes.forEach(checkbox => {
              checkbox.checked = selectAllCheckbox.checked;
          });
          updateCartCount();
      });
    
      // Remove selected items
      const editIcon = document.querySelector('.edit-icon');
      editIcon.addEventListener('click', () => {
          itemCheckboxes.forEach(checkbox => {
              if (checkbox.checked) {
                  const cartItem = checkbox.closest('.cart-item');
                  cartItem.remove();
              }
          });
    
          // Update the cart count
          updateCartCount();
      });
    
      // Update cart count
      function updateCartCount() {
          const cartCount = document.querySelectorAll('.cart-item').length;
          const cartHeader = document.querySelector('.cart-header h2 span');
          cartHeader.textContent = `(${cartCount})`;
      }
    
      // Update quantity functionality
      const cartItems = document.querySelectorAll('.cart-item');
    
      cartItems.forEach(cartItem => {
          const quantitySpan = cartItem.querySelector('.quantity span');
          const minusButton = cartItem.querySelector('.quantity button:first-child');
          const plusButton = cartItem.querySelector('.quantity button:last-child');
    
          minusButton.addEventListener('click', () => {
              let quantity = parseInt(quantitySpan.textContent);
              if (quantity > 1) {
                  quantity--;
                  quantitySpan.textContent = quantity;
                  updateCartTotal();
              }
          });
    
          plusButton.addEventListener('click', () => {
              let quantity = parseInt(quantitySpan.textContent);
              quantity++;
              quantitySpan.textContent = quantity;
              updateCartTotal();
          });
      });
    
      // Update cart total
      function updateCartTotal() {
          let total = 0;
          const cartItems = document.querySelectorAll('.cart-item'); // Fetch the latest cart items
          cartItems.forEach(cartItem => {
              const quantity = parseInt(cartItem.querySelector('.quantity span').textContent);
              const price = parseFloat(cartItem.querySelector('.item-price').textContent.replace('₱', '').replace(',', ''));
              total += quantity * price;
          });
    
          const cartFooterTotal = document.querySelector('.cart-footer .total span');
          cartFooterTotal.textContent = `₱${total.toLocaleString()}`;
      }
    
      // Initial cart count update
      updateCartCount();
    