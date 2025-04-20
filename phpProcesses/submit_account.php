<?php
include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $address = $_POST['address'] ?? null;
    $city = $_POST['city'] ?? null;
    $zipcode = $_POST['zipcode'] ?? null;

    var_dump($_POST);

    if ($firstname && $lastname && $email && $password && $address && $city && $zipcode) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (firstname, lastname, email, password, address, city, zipcode) 
                VALUES (:firstname, :lastname, :email, :password, :address, :city, :zipcode)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':zipcode', $zipcode);

        try {
            if ($stmt->execute()) {
                echo "Account created successfully!";
            } else {
                echo "Error: Unable to create account.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Error: This email is already registered.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo "Error: All fields are required.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>

phpProcesses/verify_otp.php
<?php
session_start();
include 'connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otpInput = implode('', $_POST['otp']);

    // Check if OTP exists in session
    if (!isset($_SESSION['otp'])) {
        $response['message'] = "OTP session expired or not found. Please request a new one.";
    }
    // Check if OTP has expired
    else if (isset($_SESSION['otp_expiry']) && time() > $_SESSION['otp_expiry']) {
        $response['message'] = "OTP has expired. Please request a new one.";
        $response['expired'] = true;
    }
    // Check if OTP is correct
    else if ($otpInput != $_SESSION['otp']) {
        $response['message'] = "Invalid OTP. Please try again.";
    }
    // OTP is valid
    else {
        $_SESSION['step'] = 'location';
        $response['success'] = true;
        $response['message'] = "OTP verified successfully.";

        // Clean up OTP-related session variables
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        unset($_SESSION['resend_count']);
        unset($_SESSION['last_resend']);
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}