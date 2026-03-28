<?php
/**
 * Login API
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
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitizeInput($conn, $_POST['email']);
        $password = $_POST['password']; // Don't sanitize password before verification
    } elseif ($data && isset($data['email']) && isset($data['password'])) {
        $email = sanitizeInput($conn, $data['email']);
        $password = $data['password'];
    } else {
        sendJsonResponse(false, 'Email and password are required');
    }
} else {
    sendJsonResponse(false, 'Invalid request method');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Prepare and execute query (SECURE - prevents SQL injection)
$stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendJsonResponse(false, 'Invalid email or password');
}

$user = $result->fetch_assoc();

// Verify password using password_verify (SECURE - password is hashed)
if (!password_verify($password, $user['password'])) {
    sendJsonResponse(false, 'Invalid email or password');
}

// Set user session
setUserSession($user);

// Return success response
sendJsonResponse(true, 'Login successful', [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'role' => $user['role']
]);
?>
