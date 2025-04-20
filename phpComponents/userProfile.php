<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="\css\index.css">
</head>
<body>
<div class="relative" id="profile-dropdown-container">
    <img id="UserAvatar" src="assets/Generic-avatar.png" alt="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" class="cursor-pointer">
    
    <!-- Profile Dropdown -->
    <div id="profileDropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg hidden z-50">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <img src="assets/Generic-avatar.png" alt="Profile" class="w-12 h-12 rounded-full">
                <div>
                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="p-2">
            <a href="profile.php" class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>My Profile</span>
            </a>
            
            <a href="orders.php" class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>My Orders</span>
            </a>
            
            <a href="wishlist.php" class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Wishlist</span>
            </a>
            
            <a href="account-settings.php" class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Account Settings</span>
            </a>
        </div>
        
        <div class="border-t border-gray-200 p-2">
            <a href="phpProcesses/logout.php" class="flex items-center p-2 text-red-500 hover:bg-red-50 rounded-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>
</body>
</html>