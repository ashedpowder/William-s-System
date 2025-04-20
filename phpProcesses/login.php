<?php
session_start();
include 'connection.php';

// Set content type to JSON for AJAX responses
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'missing_fields', 'message' => 'Please enter both email and password.']);
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'error' => 'email_not_found', 'message' => 'Email not found.']);
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            echo json_encode(['success' => false, 'error' => 'invalid_password', 'message' => 'Incorrect password.']);
            exit();
        }

        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

        $firstInitial = strtoupper(substr($user['firstname'], 0, 1));
        $lastInitial = strtoupper(substr($user['lastname'], 0, 1));
        $_SESSION['user_initials'] = $firstInitial . $lastInitial;
        
        echo json_encode(['success' => true, 'message' => 'Login successful']);
        exit();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'database_error', 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
} else {
    // If not a POST request
    echo json_encode(['success' => false, 'error' => 'invalid_request', 'message' => 'Invalid request method.']);
    exit();
}