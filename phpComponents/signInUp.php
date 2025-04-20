<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="\css\index.css">
</head>
<body>
<section id="loginDisplay" class="loginDisplay">
            <div class="loginContainer">
                <div class="headerContainer flex flex-col justify-center align-center">
                    <img class="w-[100%] h-20 object-contain" src="assets/logo.png" alt="Williams Logo">
                    <p class="text-center spacing-2 font-semibold leading-tight">William<br>Records</p>
                </div>
                <div class="loginForm">
                    <form id="loginForm" action="phpProcesses/login.php" method="POST">
                        <label for="loginEmail">Email</label><br>
                        <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter your email" required><br>

                        <label for="loginPassword">Password</label><br>
                        <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter your Password" required><br>

                        <span class="rmfp">
                            <span>
                                <input type="checkbox" id="rememberMe" name="rememberMe">
                                <p for="rememberMe">Remember me</p>
                            </span>
                            <p class="forgotPassword">Forgot password?</p>
                        </span>
                        <div id="login-error-container"></div>
                        <span class="myLoginBtn">
                            <button class="loginBtn" type="submit">Login</button>
                        </span>
                        <div class="dontHaveAccount">
                            <p class="accCreation">Don't have an account?</p>
                            <a class="redirect" id="accCreate">Create account</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="registration" id="regisContainer">
            <div class="bar"></div>
            <div class="register-form">
                <!-- Account Form -->
                <form id="account-form" action="/phpProcesses/register.php" method="post" style="display: <?php echo (isset($_SESSION['step']) && $_SESSION['step'] == 'account') ? 'flex' : 'none'; ?>">
                    <h1 class="ca1 font-bold text-3xl">Create Account</h1>
                    <div class="name-fields">
                        <div class="name-field">
                            <label for="firstname">First Name</label>
                            <input class="inputPadding" type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="name-field">
                            <label for="lastname">Last Name</label>
                            <input class="inputPadding" type="text" id="lastname" name="lastname" required>
                        </div>
                    </div>
                    <div class="epp">
                        <label for="email">Email</label>
                        <input class="inputPadding" type="email" id="email" name="email" required>
                    </div>
                    <div class="epp password">
                        <label for="password">Password</label>
                        <input class="inputPadding" type="password" id="password" name="password"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,12}$"
                            minlength="6" maxlength="12" required>
                    </div>
                    <div class="epp password">
                        <label for="confirm-password">Confirm Password</label>
                        <input class="inputPadding" type="password" id="confirm-password" name="confirm-password"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,12}$"
                            minlength="6" maxlength="12" required>
                    </div>
                    <div class="CBagreement">
                        <input class="inputPadding myCheckbox" type="checkbox" id="agreement" name="agreement" required>
                        <label for="agreement">I agree to the Terms and Conditions</label>
                    </div>
                    <button class="nextBtn" type="submit">Continue</button>
                    <div class="haveAccount">
                        <p>Already have an account?</p>
                        <a class="redirect" id="accLogin">Login</a>
                    </div>
                </form>

                <!-- OTP Form -->
                <div id="otp" class="otpFormContainer" style="display: <?php echo (isset($_SESSION['step']) && $_SESSION['step'] == 'account') ? 'flex' : 'none'; ?>">
                    <img src="assets/williams-black.png" alt="">
                    <h2 class="otpSpacing">Enter OTP</h2>
                    <p class="otpSpacing">A 6-digit code has been sent to your email</p>
                    <form id="otpForm" action="/phpProcesses/verify_otp.php" method="post" autocomplete="off">
                        <div class="otpForm otpSpacing">
                            <?php for ($i = 0; $i < 6; $i++): ?>
                                <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
                            <?php endfor; ?>
                        </div>
                        <a class="resend otpSpacing" href="">Resend Code</a>
                        <button type="submit">Verify</button>
                    </form>
                </div>

                <!-- Location Form -->
                <form class="location-form" id="location-form" action="/phpProcesses/save_location.php" method="post" style="display: <?php echo $_SESSION['step'] == 'account' ? 'flex' : 'none'; ?>">
                    <h1 class="ca2 font-bold text-3xl">Create Account</h1>
                    <div class="rpIn">
                        <div class="rp">
                            <label for="Region">Region</label>
                            <input class="inputPadding" type="text" id="Region" name="Region" required>
                        </div>
                        <div class="rp">
                            <label for="Province">Province</label>
                            <input class="inputPadding" type="text" id="Province" name="Province" required>
                        </div>
                    </div>
                    <div class="epp">
                        <label for="city">City</label>
                        <input class="inputPadding" type="text" id="city" name="city" required>
                    </div>
                    <div class="epp">
                        <label for="zipcode">Zip Code</label>
                        <input class="inputPadding" type="text" id="zipcode" name="zipcode" required>
                    </div>
                    <div class="CBagreement">
                        <input class="inputPadding myCheckbox" type="checkbox" id="agreements" name="agreements" required>
                        <label for="agreements">I agree to the Terms and Conditions</label>
                    </div>
                    <div class="form-buttons">
                        <button type="submit">Submit</button>
                    </div>
                </form>

                <!-- Feedback Messages -->
                <div id="message-container" style="display: none;">
                    <div class="alert" id="alert-message"></div>
                </div>
            </div>
        </section>
</body>
</html>