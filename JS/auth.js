/**
 * Authentication Module
 * Handles user registration, login, and OTP verification
 */
function initializeAuthentication() {
    // DOM Elements
    const elements = {
        overlay: document.getElementById('overlay'),
        triggerImage: document.getElementById('myAvatar'),
        signupLink: document.getElementById('signupLink'),
        loginDisplay: document.getElementById('loginDisplay'),
        regisContainer: document.getElementById('regisContainer'),
        accLogin: document.getElementById('accLogin'),
        accCreate: document.getElementById('accCreate'),
        checkboxes: document.getElementsByClassName('myCheckbox'),
        accountForm: document.getElementById('account-form'),
        otpForm: document.getElementById('otpForm'),
        locationForm: document.getElementById('location-form'),
        otpInputs: document.querySelectorAll('.otp-input'),
        resendLink: document.querySelector('.resend')
    };
    
    // OTP Timer state
    const otpState = {
        timer: null,
        cooldownTimer: null,
        timeLeft: 300, // 5 minutes in seconds
        resendCooldown: 0
    };
    
    // Get current step from PHP session
    const currentStep = '<?php echo isset($_SESSION["step"]) ? $_SESSION["step"] : "account"; ?>';
    
    // Initialize UI based on URL
    initializeUI();
    
    // Set up event listeners
    setupEventListeners();
    
    // Initialize form handlers
    setupFormHandlers();
    
    // Initialize OTP functionality
    setupOtpFunctionality();
    
    /**
     * Initializes UI state based on URL parameters and session
     */
    function initializeUI() {
        // Check if we need to show registration forms based on URL
        elements.overlay.style.display = checkUrlForRegistration() ? 'flex' : 'none';
        
        // Set up checkboxes synchronized behavior
        for (let i = 0; i < elements.checkboxes.length; i++) {
            elements.checkboxes[i].addEventListener('change', (event) => {
                const isChecked = event.target.checked;
                for (let j = 0; j < elements.checkboxes.length; j++) {
                    elements.checkboxes[j].checked = isChecked;
                }
            });
        }
    }
    
    /**
     * Checks URL for registration related parameters
     * @returns {boolean} Whether registration should be shown
     */
    function checkUrlForRegistration() {
        // Get URL hash if present
        const urlHash = window.location.hash;
        
        // If URL contains specific registration-related hashes, show the overlay and correct form
        if (urlHash === '#regisContainer' || urlHash === '#otp') {
            showRegistrationForm();
            return true;
        }
        return false;
    }
    
    /**
     * Sets up event listeners for UI interactions
     */
    function setupEventListeners() {
        // Handle overlay clicks and UI navigation
        document.addEventListener('click', (event) => {
            if (event.target === elements.overlay) {
                hideOverlay();
                resetForms();
            }
            else if (event.target === elements.signupLink) {
                event.preventDefault();
                showOverlay();
                elements.loginDisplay.style.display = 'none';
                elements.regisContainer.style.display = 'flex';
                showRegistrationForm();
            }
            else if (event.target === elements.triggerImage) {
                event.preventDefault();
                showOverlay();
                elements.regisContainer.style.display = 'none';
                elements.loginDisplay.style.display = 'flex';
            }
            else if (event.target === elements.accCreate) {
                event.preventDefault();
                showOverlay();
                showRegistrationForm();
            }
            else if (event.target === elements.accLogin) {
                event.preventDefault();
                showOverlay();
                elements.regisContainer.style.display = 'none';
                elements.loginDisplay.style.display = 'flex';
            }
        });
    }
    
    /**
     * Shows the overlay
     */
    function showOverlay() {
        elements.overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Hides the overlay
     */
    function hideOverlay() {
        elements.overlay.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    /**
     * Reset all forms to their initial state
     */
    function resetForms() {
        // Reset account form
        if (elements.accountForm) {
            elements.accountForm.reset();
            const submitButton = elements.accountForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.textContent = 'Continue';
                submitButton.disabled = false;
            }
        }
        
        // Reset OTP form
        if (elements.otpForm) {
            elements.otpForm.reset();
            const verifyButton = elements.otpForm.querySelector('button[type="submit"]');
            if (verifyButton) {
                verifyButton.textContent = 'Verify';
                verifyButton.disabled = false;
            }
        }
        
        // Reset location form
        if (elements.locationForm) {
            elements.locationForm.reset();
        }
        
        // Clear error messages
        clearErrorMessages();
        
        // Stop OTP timer
        if (otpState.timer) {
            clearInterval(otpState.timer);
            otpState.timer = null;
        }
        
        // Reset display
        elements.regisContainer.style.display = 'none';
        elements.loginDisplay.style.display = 'none';
    }
    
    /**
     * Shows appropriate registration form based on current step
     */
    function showRegistrationForm() {
        elements.loginDisplay.style.display = 'none';
        elements.regisContainer.style.display = 'flex';
        
        // Hide all forms first
        document.getElementById('account-form').style.display = 'none';
        document.getElementById('otp').style.display = 'none';
        document.getElementById('location-form').style.display = 'none';
        
        // Show only the current step form
        if (currentStep === 'otp') {
            document.getElementById('otp').style.display = 'flex';
            startOtpTimer();
        } else if (currentStep === 'location') {
            document.getElementById('location-form').style.display = 'flex';
        } else {
            document.getElementById('account-form').style.display = 'flex';
        }
    }
    
    function setupFormHandlers() {
        // Account form handler
        if (elements.accountForm) {
            elements.accountForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get submit button and save original text
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton ? submitButton.textContent : 'Continue';
                
                // Show loading state
                if (submitButton) {
                    submitButton.textContent = 'Processing...';
                    submitButton.disabled = true;
                }
                
                fetch('/phpProcesses/register.php', {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show OTP form
                        document.getElementById('account-form').style.display = 'none';
                        document.getElementById('otp').style.display = 'flex';
                        startOtpTimer();
                        clearErrorMessages();
                    } else {
                        // Show error message
                        const messageContainer = document.getElementById('message-container');
                        const alertMessage = document.getElementById('alert-message');
                        if (messageContainer && alertMessage) {
                            alertMessage.textContent = data.message;
                            alertMessage.className = 'alert error';
                            messageContainer.style.display = 'block';
                        } else {
                            alert(data.message);
                        }
                        
                        // Reset button state
                        if (submitButton) {
                            submitButton.textContent = originalText;
                            submitButton.disabled = false;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset button state
                    if (submitButton) {
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    }
                    
                    // Show error message
                    alert('Network error. Please try again.');
                });
            });
        }
        
        // OTP form handler with loading animation
        if (elements.otpForm) {
            elements.otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearErrorMessages();
                
                // Get verify button
                const verifyButton = this.querySelector('button[type="submit"]');
                const originalText = verifyButton ? verifyButton.innerHTML : 'Verify';
                
                // Change button content to loading spinner
                if (verifyButton) {
                    verifyButton.innerHTML = '<span class="spinner"></span>';
                    verifyButton.disabled = true;
                }
                
                // Collect OTP values and create form data
                const otpValues = Array.from(this.querySelectorAll('.otp-input')).map(input => input.value);
                const formData = new FormData();
                otpValues.forEach((value, index) => formData.append('otp[]', value));
                
                // Send AJAX request
                fetch('/phpProcesses/verify_otp.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // After 2-3 seconds, show check mark
                    setTimeout(() => {
                        if (verifyButton) {
                            verifyButton.innerHTML = data.success ? 
                                '<span class="check-mark">✓</span>' : 
                                '<span class="error-mark">✗</span>';
                        }
                        
                        if (data.success) {
                            // Clear any error messages on success
                            clearErrorMessages();
                            
                            // Stop the OTP timer
                            if (otpState.timer) {
                                clearInterval(otpState.timer);
                                otpState.timer = null;
                            }
                            
                            setTimeout(() => {
                                document.getElementById('otp').style.display = 'none';
                                document.getElementById('location-form').style.display = 'flex';
                            }, 1000);
                        } else {
                            // Show error message
                            const messageContainer = document.getElementById('message-container');
                            const alertMessage = document.getElementById('alert-message');
                            if (messageContainer && alertMessage) {
                                alertMessage.textContent = data.message;
                                alertMessage.className = 'alert error';
                                messageContainer.style.display = 'block';
                                
                                // Reset button after 1 second
                                setTimeout(() => {
                                    if (verifyButton) {
                                        verifyButton.innerHTML = originalText;
                                        verifyButton.disabled = false;
                                    }
                                }, 1000);
                            }
                        }
                    }, 2500); // 2.5 second delay for loading animation
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button on error
                    if (verifyButton) {
                        verifyButton.innerHTML = originalText;
                        verifyButton.disabled = false;
                    }
                    
                    // Show error message
                    const messageContainer = document.getElementById('message-container');
                    const alertMessage = document.getElementById('alert-message');
                    if (messageContainer && alertMessage) {
                        alertMessage.textContent = 'Network error. Please try again.';
                        alertMessage.className = 'alert error';
                        messageContainer.style.display = 'block';
                    }
                });
            });
        }
        
        // Location form handler
        if (elements.locationForm) {
            elements.locationForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get submit button and save original text
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton ? submitButton.textContent : 'Submit';
                
                // Show loading state
                if (submitButton) {
                    submitButton.textContent = 'Processing...';
                    submitButton.disabled = true;
                }
                
                fetch('/phpProcesses/save_location.php', {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Registration complete - show success message and login form
                        alert(data.message);
                        elements.regisContainer.style.display = 'none';
                        elements.loginDisplay.style.display = 'flex';
                    } else {
                        alert(data.message);
                        
                        // Reset button state
                        if (submitButton) {
                            submitButton.textContent = originalText;
                            submitButton.disabled = false;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset button state
                    if (submitButton) {
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    }
                    
                    alert('Network error. Please try again.');
                });
            });
        }
    }
    
    /**
     * Helper function to clear error messages
     */
    function clearErrorMessages() {
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.style.display = 'none';
        }
        
        const alertMessage = document.getElementById('alert-message');
        if (alertMessage) {
            alertMessage.textContent = '';
            alertMessage.className = '';
        }
    }
    
    /**
     * Starts the OTP expiry timer
     */
    function startOtpTimer() {
        // Clear any existing timer
        if (otpState.timer) clearInterval(otpState.timer);
        
        // Reset time to 5 minutes
        otpState.timeLeft = 300;
        
        // Setup timer display
        setupOtpTimerDisplay();
        
        // Start countdown
        otpState.timer = setInterval(function() {
            otpState.timeLeft--;
            
            // Update timer display
            const timerElement = document.querySelector('.otp-timer');
            if (timerElement) {
                timerElement.textContent = `Code expires in: ${formatTime(otpState.timeLeft)}`;
                timerElement.style.color = otpState.timeLeft < 60 ? '#d9534f' : '#555';
            }
            
            // When timer expires
            if (otpState.timeLeft <= 0) {
                clearInterval(otpState.timer);
                otpState.timer = null;
                
                // Show expired message
                const timerElement = document.querySelector('.otp-timer');
                if (timerElement) {
                    timerElement.textContent = 'OTP has expired. Please request a new one.';
                    timerElement.style.color = '#d9534f';
                }
            }
        }, 1000);
    }
    
    /**
     * Creates or updates the OTP timer display element
     */
    function setupOtpTimerDisplay() {
        // Check if timer element already exists
        let timerElement = document.querySelector('.otp-timer');
        
        if (!timerElement) {
            // Create timer element
            timerElement = document.createElement('p');
            timerElement.className = 'otp-timer otpSpacing';
            timerElement.style.textAlign = 'center';
            timerElement.style.color = '#282727';
            
            // Insert after the "A 6-digit code has been sent" paragraph
            const otpContainer = document.getElementById('otp');
            const afterElement = otpContainer.querySelector('p.otpSpacing');
            if (afterElement && otpContainer) {
                otpContainer.insertBefore(timerElement, afterElement.nextSibling);
            }
        }
        
        // Update timer text
        timerElement.textContent = `Code expires in: ${formatTime(otpState.timeLeft)}`;
    }
    
    /**
     * Starts the cooldown timer for OTP resend
     */
    function startResendCooldown() {
        // Clear any existing cooldown timer
        if (otpState.cooldownTimer) clearInterval(otpState.cooldownTimer);
        
        // Set cooldown to 30 seconds
        otpState.resendCooldown = 30;
        
        // Update button state
        updateResendButton();
        
        // Start countdown
        otpState.cooldownTimer = setInterval(function() {
            otpState.resendCooldown--;
            updateResendButton();
            
            // When cooldown ends
            if (otpState.resendCooldown <= 0) {
                clearInterval(otpState.cooldownTimer);
                otpState.cooldownTimer = null;
            }
        }, 1000);
    }
    
    /**
     * Updates the resend button state based on cooldown
     */
    function updateResendButton() {
        const resendLink = document.querySelector('.resend');
        if (!resendLink) return;
        
        const isCooldown = otpState.resendCooldown > 0;
        resendLink.textContent = isCooldown ? `Resend Code (${otpState.resendCooldown}s)` : 'Resend Code';
        resendLink.style.pointerEvents = isCooldown ? 'none' : '';
        resendLink.style.color = isCooldown ? '#282727' : '';
    }
    
    /**
     * Setup OTP input field functionality
     */
    function setupOtpFunctionality() {
        // Set up OTP input fields
        if (elements.otpInputs) {
            elements.otpInputs.forEach((input, index) => {
                // Auto-focus next input on entry
                input.addEventListener('input', () => {
                    if (input.value.length === 1 && index < elements.otpInputs.length - 1) {
                        elements.otpInputs[index + 1].focus();
                    }
                });
                
                // Handle backspace to go to previous input
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                        elements.otpInputs[index - 1].focus();
                    }
                });
            });
        }
        
        // Set up resend functionality
        if (elements.resendLink) {
            elements.resendLink.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Don't do anything if in cooldown
                if (otpState.resendCooldown > 0) return;
                
                fetch('/phpProcesses/resend_otp.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('A new OTP has been sent to your email.');
                        
                        // If we're already displaying the OTP form, restart the timer
                        if (document.getElementById('otp').style.display === 'flex') {
                            // Restart timers
                            startOtpTimer();
                            startResendCooldown();
                        }
                    } else {
                        alert(data.message || 'Failed to resend OTP. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please try again.');
                });
            });
        }
    }
}

// Single initialization at DOM content loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeAuthentication();
    initializeCarousel(); // Keep other initializations
});

// Remove duplicate event listener for OTP inputs - this is already handled in initializeAuthentication