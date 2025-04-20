<?php
session_start();
include 'connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $region = trim($_POST['Region']);
    $province = trim($_POST['Province']);
    $city = trim($_POST['city']);
    $zipcode = trim($_POST['zipcode']);
    $agreements = isset($_POST['agreements']) ? 1 : 0;

    $errors = [];

    // Add validation here if needed
    if (empty($region)) {
        $errors[] = "Region is required.";
    }
    if (empty($province)) {
        $errors[] = "Province is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }
    if (empty($zipcode)) {
        $errors[] = "Zip code is required.";
    }
    if (!$agreements) {
        $errors[] = "You must agree to the terms and conditions.";
    }

    if (!empty($errors)) {
        $response['message'] = implode(" ", $errors);
    } else {
        if (!isset($_SESSION['user_data'])) {
            $response['message'] = "Session expired. Please start over.";
        } else {
            $userData = $_SESSION['user_data'];
            try {
                $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, region, province, city, zipcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $userData['firstname'],
                    $userData['lastname'],
                    $userData['email'],
                    $userData['password'],
                    $region,
                    $province,
                    $city,
                    $zipcode
                ]);

                // Clear registration session data
                unset($_SESSION['user_data'], $_SESSION['otp'], $_SESSION['step']);

                $response['success'] = true;
                $response['message'] = "Registration successful. Please login.";
            } catch (PDOException $e) {
                $response['message'] = "Database error: " . $e->getMessage();
            }
        }
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
