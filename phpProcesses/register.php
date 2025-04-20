<?php
// register.php - Updated version
session_start();
include 'connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize inputs
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $agreement = isset($_POST['agreement']) ? 1 : 0;

    // Validation logic
    if (empty($firstname)) {
        $response['message'] = "First name is required.";
    } elseif (empty($lastname)) {
        $response['message'] = "Last name is required.";
    } elseif (!$email) {
        $response['message'] = "Invalid email address.";
    } elseif (strlen($password) < 6 || strlen($password) > 12) {
        $response['message'] = "Password must be between 6 and 12 characters.";
    } elseif ($password !== $confirm_password) {
        $response['message'] = "Passwords do not match.";
    } elseif (!$agreement) {
        $response['message'] = "You must agree to the terms and conditions.";
    } else {
        // Check if email already exists
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $response['message'] = "Email is already registered.";
            } else {
                // Generate OTP
                $otp = mt_rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['user_data'] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];

                // Send OTP via PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'williamsstore1995@gmail.com';
                    $mail->Password = 'iaar dqex ppsy zrrq';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('williamsstore1995@gmail.com', 'Williams Record');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body = "Your OTP code is: <b>$otp</b>";

                    $mail->send();

                    // Set session step and respond with success
                    $_SESSION['otp'] = $otp;
                    $_SESSION['otp_expiry'] = time() + (5 * 60); // 5 minutes expiry
                    $_SESSION['resend_count'] = 0;
                    $_SESSION['last_resend'] = time();
                    $response['success'] = true;
                    $response['message'] = "OTP sent to your email. Please verify.";
                } catch (Exception $e) {
                    $response['message'] = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } catch (PDOException $e) {
            $response['message'] = "Database error: " . $e->getMessage();
        }
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



