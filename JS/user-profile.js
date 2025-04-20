// Add this to your js/auth.js file or create a new file

document.addEventListener('DOMContentLoaded', function() {
    const userAvatar = document.getElementById('UserAvatar');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (userAvatar && profileDropdown) {
        // Toggle dropdown when clicking on avatar
        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && e.target !== userAvatar) {
                profileDropdown.classList.add('hidden');
            }
        });
    }
    
    // Logout functionality
    const logoutLink = document.querySelector('a[href="phpProcesses/logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // You could add a confirmation if desired
            if (confirm('Are you sure you want to logout?')) {
                // Send AJAX request to logout.php
                fetch('phpProcesses/logout.php', {
                    method: 'POST',
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => {
                    console.error('Logout error:', error);
                });
            }
        });
    }
});