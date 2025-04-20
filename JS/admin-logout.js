function toggleLogoutMenu() {
    const menu = document.getElementById('logout-menu');
    if (menu.style.display === 'none' || menu.style.display === '') {
      menu.style.display = 'block';
    } else {
      menu.style.display = 'none';
    }
  }
  document.addEventListener('click', function (event) {
    const menu = document.getElementById('logout-menu');
    const chevron = document.querySelector('.admin-chevron-down');
    if (!menu.contains(event.target) && !chevron.contains(event.target)) {
      menu.style.display = 'none';
    }
  });

  // To style the confirmation message, you cannot directly style the native `window.confirm` dialog.
  // Instead, you can create a custom modal dialog for the confirmation message.

  function showCustomConfirmation(message, onConfirm, onCancel) {
    const modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = '50%';
    modal.style.left = '50%';
    modal.style.transform = 'translate(-50%, -50%)';
    modal.style.backgroundColor = '#ffffff';
    modal.style.padding = '20px 10px';
    modal.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.5)';
    modal.style.zIndex = '1000';
    modal.style.borderRadius = '8px';
    modal.style.textAlign = 'center';
    modal.style.width = '400px';
    modal.style.maxWidth = '1200px'; // Responsive width
    modal.style.height = '180px';
    modal.style.maxHeight = '100vh'; // Responsive height
    modal.style.overflowY = 'auto'; // Allow scrolling if content is too long
    modal.style.display = 'flex';
    modal.style.flexDirection = 'column';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.fontSize= '1.1rem';
    modal.style.marginTop = '10px'; // Add margin to the top
    modal.style.marginBottom = '10px'; // Add margin to the bottom
    modal.style.border = '1px solid #ddd'; // Add a border to the modal



    const messageElem = document.createElement('p');
    messageElem.textContent = message;
    modal.appendChild(messageElem);

    const buttonContainer = document.createElement('div');
    buttonContainer.style.marginTop = '10px';

    const confirmButton = document.createElement('button');
    confirmButton.textContent = 'Yes';
    confirmButton.style.marginRight = '10px';
    confirmButton.style.padding = '10px 20px';
    confirmButton.style.backgroundColor = '#861919';
    confirmButton.style.color = '#fff';
    confirmButton.style.border = 'none';
    confirmButton.style.borderRadius = '4px';
    confirmButton.style.cursor = 'pointer';
    confirmButton.style.transition = 'background-color 0.3s ease';
    confirmButton.style.width = '100px'; // Set a fixed width for the button
    confirmButton.addEventListener('click', function () {
      document.body.removeChild(modal);
      onConfirm();
    });

    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'No';
    cancelButton.style.padding = '10px 25px';
    cancelButton.style.backgroundColor = '#000000';
    cancelButton.style.color = '#fff';
    cancelButton.style.border = 'none';
    cancelButton.style.borderRadius = '4px';
    cancelButton.style.cursor = 'pointer';
    cancelButton.style.marginLeft = '10px';
    cancelButton.style.width = '100px'; // Set a fixed width for the button

    cancelButton.addEventListener('click', function () {
      document.body.removeChild(modal);
      onCancel();
    });

    buttonContainer.appendChild(confirmButton);
    buttonContainer.appendChild(cancelButton);
    modal.appendChild(buttonContainer);

    document.body.appendChild(modal);
  }

  // Replace the native confirm with the custom confirmation dialog
  document.getElementById('logout-menu').addEventListener('click', function (event) {
    if (event.target.tagName === 'A' && event.target.textContent.trim() === 'Logout') {
      event.preventDefault(); // Prevent default action initially
      showCustomConfirmation(
        'Are you sure you want to logout?',
        function () {
          // User confirmed
          window.location.href = event.target.href;
        },
        function () {
          // User canceled
          console.log('Logout canceled');
        }
      );
    }
  });