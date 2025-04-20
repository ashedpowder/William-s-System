/**
 * Login Verification Function
 * Handles user login verification with error handling
 */
function initializeLoginVerification() {
    // DOM Elements
    const elements = {
        loginForm: document.getElementById('loginForm'),
        loginEmail: document.getElementById('loginEmail'),
        loginPassword: document.getElementById('loginPassword')
    };
    
    // Set up event listeners
    setupLoginHandler();
    
    /**
     * Sets up login form submission handler
     */
    function setupLoginHandler() {
        if (elements.loginForm) {
            elements.loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear any previous error messages
                clearLoginErrorMessages();
                
                // Get form data
                const email = elements.loginEmail.value.trim();
                const password = elements.loginPassword.value;
                
                // Validate form data
                if (!email || !password) {
                    showLoginError('Please enter both email and password.');
                    return;
                }
                
                // Create FormData object
                const formData = new FormData(this);
                
                // Show loading state
                const loginButton = this.querySelector('button[type="submit"]');
                const originalButtonText = loginButton ? loginButton.innerHTML : 'Login';
                if (loginButton) {
                    loginButton.innerHTML = '<span class="spinner"></span>';
                    loginButton.disabled = true;
                }
                
                // Send AJAX request to login.php
                fetch('/phpProcesses/login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Login successful
                        clearLoginErrorMessages();
                        
                        // Redirect to user.php or dashboard
                        window.location.href = '/index.php';
                    } else {
                        // Show appropriate error message
                        let errorMessage = 'Login failed. Please try again.';
                        
                        if (data.error === 'email_not_found') {
                            errorMessage = 'Email not found.';
                        } else if (data.error === 'invalid_password') {
                            errorMessage = 'Incorrect password. Please try again.';
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        
                        showLoginError(errorMessage);
                        
                        // Reset button
                        if (loginButton) {
                            loginButton.innerHTML = originalButtonText;
                            loginButton.disabled = false;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showLoginError('Network error. Please try again.');
                    
                    // Reset button
                    if (loginButton) {
                        loginButton.innerHTML = originalButtonText;
                        loginButton.disabled = false;
                    }
                });
            });
        }
    }
    
    /**
     * Shows login error message
     * @param {string} message - Error message to display
     */
    function showLoginError(message) {
        // Check if error container exists, if not create one
        let errorContainer = document.getElementById('login-error-container');
        
        // Set error message
        errorContainer.textContent = message;
        errorContainer.style.display = '';
        
        // Ensure loginDisplay stays visible
        const loginDisplay = document.getElementById('loginDisplay');
        if (loginDisplay) {
            loginDisplay.style.display = 'block';
        }
    }
    
    /**
     * Clears login error messages
     */
    function clearLoginErrorMessages() {
        const errorContainer = document.getElementById('login-error-container');
        if (errorContainer) {
            errorContainer.style.display = 'none';
            errorContainer.textContent = '';
        }
    }
}

// Initialize login verification when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeLoginVerification();


    const logoutElement = document.getElementById('logout');
    
    if (logoutElement) {
        logoutElement.addEventListener('click', function() {
            // Send request to logout script
            fetch('/phpProcesses/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to home page or refresh
                        window.location.href = '/index.php';
                    }
                })
                .catch(error => {
                    console.error('Logout error:', error);
                });
        });
    }
});
