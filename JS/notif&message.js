function toggleNotificationModal() {
    const modal = document.getElementById('notificationModal');
    modal.style.display = modal.style.display === 'none' || modal.style.display === '' ? 'block' : 'none';
}

function toggleMessageModal() {
    const modal = document.getElementById('messageModal');
    modal.style.display = modal.style.display === 'none' || modal.style.display === '' ? 'block' : 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const notificationModal = document.getElementById('notificationModal');
    const messageModal = document.getElementById('messageModal');
    if (event.target === notificationModal) {
        notificationModal.style.display = 'none';
    }
    if (event.target === messageModal) {
        messageModal.style.display = 'none';
    }
};