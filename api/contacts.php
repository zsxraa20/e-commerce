<?php
/**
 * Contacts API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        requireAdmin();
        getAllContacts($conn);
        break;
        
    case 'POST':
        // Already handled by contact.php
        sendJsonResponse(false, 'Use contact.php for creating messages');
        break;
        
    case 'PUT':
        requireAdmin();
        updateContactStatus($conn);
        break;
        
    default:
        sendJsonResponse(false, 'Invalid request method');
}

/**
 * Get all contact messages (Admin only)
 */
function getAllContacts($conn) {
    $result = $conn->query("
        SELECT id, name, email, message, status, created_at 
        FROM contacts 
        ORDER BY id DESC
    ");
    
    $contacts = [];
    while ($contact = $result->fetch_assoc()) {
        $contacts[] = $contact;
    }
    
    sendJsonResponse(true, 'Contacts retrieved', $contacts);
}

/**
 * Update contact status (Admin only)
 */
function updateContactStatus($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['status'])) {
        sendJsonResponse(false, 'Contact ID and status are required');
    }
    
    $id = intval($data['id']);
    $status = sanitizeInput($conn, $data['status']);
    
    $validStatuses = ['new', 'read', 'replied'];
    if (!in_array($status, $validStatuses)) {
        sendJsonResponse(false, 'Invalid status');
    }
    
    $stmt = $conn->prepare("UPDATE contacts SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        sendJsonResponse(true, 'Contact status updated');
    } else {
        sendJsonResponse(false, 'Failed to update status: ' . $conn->error);
    }
}
?>
