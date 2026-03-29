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
        SELECT id, name, email, message, admin_reply, replied_at, status, created_at 
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

    if (!isset($data['id'])) {
        sendJsonResponse(false, 'Contact ID is required');
    }

    $id = intval($data['id']);

    // Reply flow: save admin reply and auto-set status to replied
    if (isset($data['reply_message'])) {
        $replyMessage = sanitizeInput($conn, $data['reply_message']);

        if (empty(trim($replyMessage))) {
            sendJsonResponse(false, 'Reply message is required');
        }

        $stmt = $conn->prepare("UPDATE contacts SET admin_reply = ?, status = 'replied', replied_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $replyMessage, $id);

        if ($stmt->execute()) {
            sendJsonResponse(true, 'Contact replied successfully');
        } else {
            sendJsonResponse(false, 'Failed to send reply: ' . $conn->error);
        }
        return;
    }

    // Status-only flow
    if (!isset($data['status'])) {
        sendJsonResponse(false, 'Status is required');
    }

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
