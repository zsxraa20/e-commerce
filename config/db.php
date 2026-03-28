<?php
/**
 * Database Configuration
 * e-PHONE E-Commerce System
 */

$host = "localhost";
$user = "ephoneuser";
$pass = "123456";
$db = "ephone";

// Create connection with error handling
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

// Set charset to handle special characters
$conn->set_charset("utf8mb4");

/**
 * Helper function to send JSON response
 */
function sendJsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

/**
 * Helper function to sanitize input
 */
function sanitizeInput($conn, $input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitizeInput($conn, $value);
        }
        return $input;
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>
