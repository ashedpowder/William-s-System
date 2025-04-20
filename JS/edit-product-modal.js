function toggleModal() {
const editProductModal = document.getElementById('editProductModal');
}
function openEditModal(productName, category, price, stock, status) {
    editProductModal.style.display = 'block';
    editProductModal.style.flexDirection = 'column';
    editProductModal.style.justifyContent = 'center';
    editProductModal.style.alignItems = 'center';
    editProductModal.style.fontSize = '1.1rem';


    const inputs = editProductModal.querySelectorAll('input, textarea');
    inputs[0].value = productName;
    inputs[1].value = price;
    inputs[2].value = stock;
    inputs[3].checked = status === 'Low Stock';
    editProductModal.querySelector('textarea').value = `Description for ${productName}`;
}

function closeEditModal() {
    editProductModal.style.display = 'none';
    body.style.filter = 'none'; // Remove blur from the background
}

function saveChanges() {
    alert('Changes saved successfully!');
    closeEditModal();
}

function deleteProduct() {
    if (confirm('Are you sure you want to delete this product?')) {
        alert('Product deleted successfully!');
        closeEditModal();
    }
}

window.onclick = function(event) {
    if (event.target === editProductModal) {
        closeEditModal();
    }
};
