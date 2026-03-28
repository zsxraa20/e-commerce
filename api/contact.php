<?php
/**
 * Contact Form API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';

// Set JSON header
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['name']) || empty($data['name'])) {
    sendJsonResponse(false, 'Name is required');
}

if (!isset($data['email']) || empty($data['email'])) {
    sendJsonResponse(false, 'Email is required');
}

if (!isset($data['message']) || empty($data['message'])) {
    sendJsonResponse(false, 'Message is required');
}

// Sanitize inputs
$name = sanitizeInput($conn, $data['name']);
$email = sanitizeInput($conn, $data['email']);
$message = sanitizeInput($conn, $data['message']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Validate message length
if (strlen($message) < 10) {
    sendJsonResponse(false, 'Message must be at least 10 characters');
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    sendJsonResponse(true, 'Message sent successfully. We will contact you soon!');
} else {
    sendJsonResponse(false, 'Failed to send message: ' . $conn->error);
}
?>
