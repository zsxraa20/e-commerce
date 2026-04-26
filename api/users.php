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

    case 'POST':
        // Admin: create user (role user)
        requireAdmin();
        createUserAdmin($conn);
        break;
        
    case 'PUT':
        if (isLoggedIn()) {
            // Admin can edit user by ID; regular user can update own profile (phone/address)
            $data = json_decode(file_get_contents('php://input'), true);
            if (isAdmin() && isset($data['id'])) {
                updateUserAdmin($conn, $data);
            } else {
                updateUserProfile($conn, $data);
            }
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
 * Update own user profile (User only: phone/address)
 */
function updateUserProfile($conn, $data) {
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

/**
 * Admin: create user (role = user)
 */
function createUserAdmin($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = isset($data['username']) ? sanitizeInput($conn, $data['username']) : '';
    $email = isset($data['email']) ? sanitizeInput($conn, $data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $phone = isset($data['phone']) ? sanitizeInput($conn, $data['phone']) : '';
    $address = isset($data['address']) ? sanitizeInput($conn, $data['address']) : '';

    if (!$username || !$email || !$password) {
        sendJsonResponse(false, 'Username, email, dan password wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, 'Format email tidak valid');
    }

    // Check uniqueness
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkRes = $checkStmt->get_result();
    if ($checkRes->num_rows > 0) {
        sendJsonResponse(false, 'Username atau email sudah digunakan');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, phone, address) VALUES (?, ?, ?, 'user', ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hash, $phone, $address);

    if ($stmt->execute()) {
        sendJsonResponse(true, 'User created', ['id' => $stmt->insert_id]);
    }

    sendJsonResponse(false, 'Failed to create user: ' . $conn->error);
}

/**
 * Admin: update user by ID (role user only)
 */
function updateUserAdmin($conn, $data) {
    requireAdmin();

    $id = intval($data['id']);
    if ($id <= 0) {
        sendJsonResponse(false, 'User ID tidak valid');
    }

    $username = isset($data['username']) ? sanitizeInput($conn, $data['username']) : '';
    $email = isset($data['email']) ? sanitizeInput($conn, $data['email']) : '';
    $phone = isset($data['phone']) ? sanitizeInput($conn, $data['phone']) : '';
    $address = isset($data['address']) ? sanitizeInput($conn, $data['address']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (!$username || !$email) {
        sendJsonResponse(false, 'Username dan email wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, 'Format email tidak valid');
    }

    // Check exists and role
    $existsStmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'user' LIMIT 1");
    $existsStmt->bind_param("i", $id);
    $existsStmt->execute();
    $existsRes = $existsStmt->get_result();
    if ($existsRes->num_rows === 0) {
        sendJsonResponse(false, 'User tidak ditemukan');
    }

    // Check uniqueness excluding current ID
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id <> ? LIMIT 1");
    $checkStmt->bind_param("ssi", $username, $email, $id);
    $checkStmt->execute();
    $checkRes = $checkStmt->get_result();
    if ($checkRes->num_rows > 0) {
        sendJsonResponse(false, 'Username atau email sudah digunakan');
    }

    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ?, password = ? WHERE id = ? AND role = 'user'");
        $stmt->bind_param("sssssi", $username, $email, $phone, $address, $hash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE id = ? AND role = 'user'");
        $stmt->bind_param("ssssi", $username, $email, $phone, $address, $id);
    }

    if ($stmt->execute()) {
        sendJsonResponse(true, 'User updated');
    }

    sendJsonResponse(false, 'Failed to update user: ' . $conn->error);
}
?>
