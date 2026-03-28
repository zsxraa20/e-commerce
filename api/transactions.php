<?php
/**
 * Transactions API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Check if admin or getting own transactions
        if (!isLoggedIn()) {
            sendJsonResponse(false, 'Please login');
        }
        
        if (isset($_GET['id'])) {
            getTransaction($conn, intval($_GET['id']));
        } else {
            getAllTransactions($conn);
        }
        break;
        
    case 'PUT':
        requireAdmin();
        updateTransactionStatus($conn);
        break;
        
    default:
        sendJsonResponse(false, 'Invalid request method');
}

/**
 * Get all transactions
 */
function getAllTransactions($conn) {
    $userId = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    $sql = "SELECT t.* FROM transactions t";
    
    // If user, only show their transactions
    if ($role !== 'admin') {
        $sql .= " WHERE t.user_id = ?";
    }
    
    $sql .= " ORDER BY t.id DESC";
    
    $stmt = $conn->prepare($sql);
    if ($role !== 'admin') {
        $stmt->bind_param("i", $userId);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($transaction = $result->fetch_assoc()) {
        $transaction['items'] = getTransactionItems($conn, $transaction['id']);
        $transaction['history'] = getTransactionHistory($conn, $transaction['id']);
        $transactions[] = $transaction;
    }
    
    sendJsonResponse(true, 'Transactions retrieved', $transactions);
}

/**
 * Get single transaction
 */
function getTransaction($conn, $id) {
    $userId = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendJsonResponse(false, 'Transaction not found');
    }
    
    $transaction = $result->fetch_assoc();
    
    // Check if user owns this transaction
    if ($role !== 'admin' && $transaction['user_id'] != $userId) {
        sendJsonResponse(false, 'Access denied');
    }
    
    $transaction['items'] = getTransactionItems($conn, $id);
    $transaction['history'] = getTransactionHistory($conn, $id);
    
    sendJsonResponse(true, 'Transaction retrieved', $transaction);
}

/**
 * Get transaction items
 */
function getTransactionItems($conn, $transactionId) {
    $stmt = $conn->prepare("SELECT * FROM transaction_items WHERE transaction_id = ?");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($item = $result->fetch_assoc()) {
        $items[] = $item;
    }
    return $items;
}

/**
 * Get transaction history
 */
function getTransactionHistory($conn, $transactionId) {
    $stmt = $conn->prepare("SELECT * FROM order_history WHERE transaction_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = [];
    while ($item = $result->fetch_assoc()) {
        $history[] = $item;
    }
    return $history;
}

/**
 * Update transaction status (Admin only)
 */
function updateTransactionStatus($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['status'])) {
        sendJsonResponse(false, 'Transaction ID and status are required');
    }
    
    $id = intval($data['id']);
    $status = sanitizeInput($conn, $data['status']);
    $notes = isset($data['notes']) ? sanitizeInput($conn, $data['notes']) : '';
    
    $validStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
        sendJsonResponse(false, 'Invalid status');
    }
    
    // Update transaction
    $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        // Add to history
        $historyStmt = $conn->prepare("INSERT INTO order_history (transaction_id, status, notes) VALUES (?, ?, ?)");
        $historyStmt->bind_param("iss", $id, $status, $notes);
        $historyStmt->execute();
        
        sendJsonResponse(true, 'Transaction status updated');
    } else {
        sendJsonResponse(false, 'Failed to update status: ' . $conn->error);
    }
}
?>
