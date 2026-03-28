<?php
/**
 * Register API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

// Get input data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received via POST or JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = sanitizeInput($conn, $_POST['username']);
        $email = sanitizeInput($conn, $_POST['email']);
        $password = $_POST['password'];
    } elseif ($data && isset($data['username']) && isset($data['email']) && isset($data['password'])) {
        $username = sanitizeInput($conn, $data['username']);
        $email = sanitizeInput($conn, $data['email']);
        $password = $data['password'];
    } else {
        sendJsonResponse(false, 'Username, email, and password are required');
    }
} else {
    sendJsonResponse(false, 'Invalid request method');
}

// Validate username
if (strlen($username) < 3 || strlen($username) > 50) {
    sendJsonResponse(false, 'Username must be between 3 and 50 characters');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Validate password strength
if (strlen($password) < 6) {
    sendJsonResponse(false, 'Password must be at least 6 characters');
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    sendJsonResponse(false, 'Email already registered');
}

// Check if username already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    sendJsonResponse(false, 'Username already taken');
}

// Hash password (SECURE)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user (default role: user)
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    $userId = $stmt->insert_id;
    
    // Get user data
    $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Set user session
    setUserSession($user);
    
    sendJsonResponse(true, 'Registration successful', [
        'user_id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role']
    ]);
} else {
    sendJsonResponse(false, 'Registration failed: ' . $conn->error);
}
?>
