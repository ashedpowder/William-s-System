document.getElementById('message-placeholder').addEventListener('click', function () {
    document.getElementById('message-modal').classList.remove('hidden');
  });

  document.getElementById('save-message').addEventListener('click', function () {
    const message = document.getElementById('message-input').value;
    if (message) {
  document.getElementById('message-placeholder').textContent = message;
    }
    document.getElementById('message-modal').classList.add('hidden');
  });

  document.getElementById('cancel-message').addEventListener('click', function () {
    document.getElementById('message-modal').classList.add('hidden');
  });

  document.getElementById('receipt-placeholder').addEventListener('click', function () {
    document.getElementById('receipt-modal').classList.remove('hidden');
  });

  document.getElementById('confirm-receipt').addEventListener('click', function () {
    document.getElementById('receipt-placeholder').textContent = 'Requested';
    document.getElementById('receipt-modal').classList.add('hidden');
  });

  document.getElementById('cancel-receipt').addEventListener('click', function () {
    document.getElementById('receipt-modal').classList.add('hidden');
  });