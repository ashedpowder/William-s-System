const editButton = document.getElementById('edit-address');
const modal = document.getElementById('edit-modal');
const saveButton = document.getElementById('save-address');
const cancelButton = document.getElementById('cancel-edit');

const nameField = document.getElementById('edit-name');
const phoneField = document.getElementById('edit-phone');
const addressField = document.getElementById('edit-address-field');
const regionField = document.getElementById('edit-region');

const customerName = document.getElementById('customer-name');
const customerPhone = document.getElementById('customer-phone');
const customerAddress = document.getElementById('customer-address');
const customerRegion = document.getElementById('customer-region');

editButton.addEventListener('click', () => {
  modal.classList.remove('hidden');
});

saveButton.addEventListener('click', () => {
  customerName.textContent = nameField.value;
  customerPhone.textContent = phoneField.value;
  customerAddress.textContent = addressField.value;
  customerRegion.textContent = regionField.value;
  modal.classList.add('hidden');
});

cancelButton.addEventListener('click', () => {
  modal.classList.add('hidden');
});