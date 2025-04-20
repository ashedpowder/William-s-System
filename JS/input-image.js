function toggleModal() {
    const modal = document.getElementById('addProductModal');
    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
  }

  function closeModal() {
    const modal = document.getElementById('addProductModal');
    modal.style.display = 'none';
  }

  document.getElementById("searchInput").addEventListener("input", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#productTable tbody tr");
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });

  // Close modal when clicking outside of it
  window.addEventListener("click", function (event) {
    const modal = document.getElementById('addProductModal');
    if (event.target === modal) {
      closeModal();
    }
  });

function triggerFileInput(button) {
    const fileInput = button.parentElement.querySelector('input[type="file"]');
    fileInput.click();
}

function previewImages(input) {
    const files = input.files;
    const previewContainer = input.parentElement.querySelector('.product-dashboard-preview-container');
    previewContainer.innerHTML = ''; // Clear previous previews
    if (files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.margin = '10px';
                img.style.borderRadius = '2px';
                img.style.cursor = 'pointer';
                img.style.transition = 'transform 0.2s';
                img.style.overflow = 'hidden';
                img.addEventListener('click', function () {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
                img.addEventListener('mouseover', function () {
                    this.style.transform = 'scale(1.05)';
                });
                img.addEventListener('mouseout', function () {
                    this.style.transform = 'scale(1)';
                });
                previewContainer.appendChild(img);
                previewContainer.appendChild(img);
                
            };
            reader.readAsDataURL(file);
        });
    }
}
