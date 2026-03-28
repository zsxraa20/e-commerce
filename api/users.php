<?php
/**
 * Users API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isLoggedIn()) {
            if (isAdmin() && !isset($_GET['profile'])) {
                getAllUsers($conn);
            } else {
                getUserProfile($conn);
            }
        } else {
            sendJsonResponse(false, 'Please login');
        }
        break;
        
    case 'PUT':
        if (isLoggedIn()) {
            updateUser($conn);
        } else {
            sendJsonResponse(false, 'Please login');
        }
        break;
        
    default:
        sendJsonResponse(false, 'Invalid request method');
}

/**
 * Get all users (Admin only)
 */
function getAllUsers($conn) {
    $result = $conn->query("
        SELECT id, username, email, role, phone, address, created_at 
        FROM users 
        WHERE role = 'user'
        ORDER BY id DESC
    ");
    
    $users = [];
    while ($user = $result->fetch_assoc()) {
        $users[] = $user;
    }
    
    sendJsonResponse(true, 'Users retrieved', $users);
}

/**
 * Get current user profile
 */
function getUserProfile($conn) {
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("
        SELECT id, username, email, role, phone, address, created_at 
        FROM users 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendJsonResponse(false, 'User not found');
    }
    
    sendJsonResponse(true, 'Profile retrieved', $result->fetch_assoc());
}

/**
 * Update user profile
 */
function updateUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];
    
    $phone = isset($data['phone']) ? sanitizeInput($conn, $data['phone']) : '';
    $address = isset($data['address']) ? sanitizeInput($conn, $data['address']) : '';
    
    $stmt = $conn->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssi", $phone, $address, $userId);
    
    if ($stmt->execute()) {
        sendJsonResponse(true, 'Profile updated successfully');
    } else {
        sendJsonResponse(false, 'Failed to update profile: ' . $conn->error);
    }
}
?>
