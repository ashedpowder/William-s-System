function getGravatarUrl(email, size = 100, defaultImage = 'identicon') {
        // Trim and lowercase the email
        const trimmedEmail = email.trim().toLowerCase();
    
        // Compute the MD5 hash of the email
        const md5Hash = CryptoJS.MD5(trimmedEmail).toString();
    
        // Construct the Gravatar URL
        return `https://www.gravatar.com/avatar/${md5Hash}?d=${encodeURIComponent(defaultImage)}&s=${size}`;
    }

    // Get DOM elements
    const notifBell = document.getElementById('notifBell');
    const shopCart = document.getElementById('shopCart');
    const signUpLink = document.querySelector('.signup');
    const myAvatar = document.getElementById('myAvatar');
    const userAvatar = document.getElementById('userAvatar');

    // Function to update header based on login state
    function updateHeader() {
        if (isLoggedIn) {
            // Hide "Sign up" link
            signUpLink.style.display = 'none';

            // Show notification bell and shopping cart
            notifBell.style.display = 'flex';
            shopCart.style.display = 'flex';

            // Replace generic avatar with user's avatar
            myAvatar.style.display = 'none'; // Hide generic avatar

            // Set the Gravatar URL for the user's avatar
            userAvatar.src = getGravatarUrl(userEmail, 100, 'identicon'); // Use Gravatar
            userAvatar.style.display = 'flex'; // Show user's avatar
        } else {
            // If not logged in, reset to default state
            signUpLink.style.display = 'inline';
            notifBell.style.display = 'none';
            shopCart.style.display = 'none';
            myAvatar.style.display = 'flex';
            userAvatar.style.display = 'none';
        }
    }

    // Call the function to update the header
    updateHeader();
