function updateTotalPayment() {
    const merchandiseSubtotal = parseFloat(
      document.getElementById('merchandise-subtotal').textContent.replace('₱', '').replace(',', '')
    ) || 0;

    const shippingSubtotal = parseFloat(
      document.getElementById('shipping-subtotal').textContent.replace('₱', '').replace(',', '')
    ) || 0;

    // Calculate the total payment
    const totalPayment = merchandiseSubtotal + shippingSubtotal;

    // Update the total payment in the DOM
    document.getElementById('total-payment').textContent = `₱${totalPayment.toLocaleString()}`;
  }

  function updateItemInfo() {
    const items = document.querySelectorAll('.item-info');
    let merchandiseSubtotal = 0;

    items.forEach(item => {
      const price = parseFloat(item.querySelector('#price').textContent.replace('₱', '').replace(',', '')) || 0;
      const quantity = parseInt(item.querySelector('#qty').textContent.replace('x', '')) || 0;

      merchandiseSubtotal += price * quantity;
    });

    // Update the merchandise subtotal in the DOM
    document.getElementById('merchandise-subtotal').textContent = `₱${merchandiseSubtotal.toLocaleString()}`;

    // Recalculate the total payment
    updateTotalPayment();
  }

  function updateShippingTotal() {
    const shippingOption = document.querySelector('.shipping-box .shipping-price');
    const shippingPrice = parseFloat(
      shippingOption.textContent.replace('₱', '').replace(',', '')
    ) || 0;

    // Update the shipping subtotal in the DOM
    document.getElementById('shipping-subtotal').textContent = `₱${shippingPrice.toLocaleString()}`;

    // Recalculate the total payment
    updateTotalPayment();
  }

  // Add event listener to update shipping price dynamically
  document.querySelectorAll('.shipping-type').forEach(option => {
    option.addEventListener('click', () => {
      updateShippingTotal();
    });
  });

  // Call the functions to ensure the totals are updated on page load
  updateItemInfo();
  updateShippingTotal();
  updateTotalPayment();