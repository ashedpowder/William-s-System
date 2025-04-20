<?php
session_start();
include 'connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

// Check if user data exists in session
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['email'])) {
    $response['message'] = "User session data not found.";
    $_SESSION['step'] = 'account'; // Reset to first step
} else {
    // Check for maximum resend attempts
    if (!isset($_SESSION['resend_count'])) {
        $_SESSION['resend_count'] = 0;
    }

    // Check if max resend limit reached
    if ($_SESSION['resend_count'] >= 5) {
        $response['message'] = "Maximum resend attempts reached. Please start over.";
        $response['reset'] = true;
        $_SESSION['step'] = 'account'; // Reset to first step
        unset($_SESSION['resend_count']);
        unset($_SESSION['last_resend']);
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
    }
    // Check cooldown period
    else if (isset($_SESSION['last_resend']) && (time() - $_SESSION['last_resend']) < 30) {
        $timeLeft = 30 - (time() - $_SESSION['last_resend']);
        $response['message'] = "Please wait $timeLeft seconds before requesting another OTP.";
        $response['cooldown'] = true;
        $response['timeLeft'] = $timeLeft;
    }
    // All checks passed, send new OTP
    else {
        // Generate new OTP
        $otp = mt_rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + (5 * 60); // 5 minutes expiry
        $_SESSION['last_resend'] = time();
        $_SESSION['resend_count']++;

        $email = $_SESSION['user_data']['email'];
        $remainingAttempts = 5 - $_SESSION['resend_count'];

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
            $mail->Subject = 'Your New OTP Code';
            $mail->Body = "Your new OTP code is: <b>$otp</b><br><br>
                          This code will expire in 5 minutes.<br>
                          Remaining resend attempts: $remainingAttempts";

            $mail->send();

            $response['success'] = true;
            $response['message'] = "New OTP sent to your email. Valid for 5 minutes.";
            $response['attempts'] = $_SESSION['resend_count'];
            $response['remaining'] = $remainingAttempts;
        } catch (Exception $e) {
            $response['message'] = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Return JSON response for AJAX
header('Content-Type: application/json');
echo json_encode($response);
exit();
