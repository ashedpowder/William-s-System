<?php
session_start();

include 'phpProcesses/connection.php';
include 'phpProcesses/carousel.php';

if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 'account';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="css\index.css">
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="nametag">
            <img class="mt-2 mb-2" src="assets/williams.png" alt="William Records Tag">
        </div>
        <section class="leftheader">
            <div class="SContainer">
                <input type="search" placeholder="Search William's....">
                <div>
                    <img src="assets/search-icon.png" alt="">
                </div>
            </div>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="#" id="signupLink" class="signup">Sign up</a>
                <img id="myAvatar" src="assets/Generic-avatar.png" alt="User Icon" style="cursor: pointer;">
            <?php else: ?>
                <img class="logFunction" src="assets/notif-bell.png" alt="notifications">
                <img class="logFunction" src="assets/shopping-cart.png" alt="shopping cart">
                <span class="username font-bold text-white text-2xl -mr-5"><?php echo htmlspecialchars($_SESSION['user_initials']); ?></span>
                <?php include 'phpComponents/userProfile.php';?>
                <?php endif; ?>
        </section>
    </header>
    <section class="navigation">
    <div class="category-item" data-category="music">
        <h3>Music</h3>
        <span class="arrow">&#8249;</span>
    </div>
    <div class="category-item" data-category="sports">
        <h3>Sports</h3>
        <span class="arrow">&#8249;</span>
    </div>
    <h3>Services</h3>
    <h3 class="ab">About us</h3>
    </section>
    <main id="dynamicContent">
    <?php include 'phpComponents/landing.php';?>
    </main>

    <div class="overlay" id="overlay">
    <?php include 'phpComponents/signInUp.php';?>
    </div>
    <script src="js/index.js"></script>
    <script src="js/registration.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/carousel.js"></script>
    <script src="js/login.js"></script>
    <script src="js/user-profile.js"></script>
    <script src="js/category.js"></script>
</body>

</html>