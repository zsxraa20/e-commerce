<?php
/**
 * Admin Statistics API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

// Require admin access
requireAdmin();

// Get statistics
$stats = [
    'total_products' => 0,
    'total_transactions' => 0,
    'total_users' => 0,
    'low_stock' => 0,
    'recent_transactions' => [],
    'recent_products' => []
];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$stats['total_products'] = $result->fetch_assoc()['count'];

// Total transactions
$result = $conn->query("SELECT COUNT(*) as count FROM transactions");
$stats['total_transactions'] = $result->fetch_assoc()['count'];

// Total users
$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
$stats['total_users'] = $result->fetch_assoc()['count'];

// Low stock products (less than 10)
$result = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock < 10");
$stats['low_stock'] = $result->fetch_assoc()['count'];

// Recent transactions
$result = $conn->query("
    SELECT t.id, t.customer_name, t.total_amount, t.status, t.created_at 
    FROM transactions t 
    ORDER BY t.id DESC 
    LIMIT 5
");
while ($row = $result->fetch_assoc()) {
    $stats['recent_transactions'][] = $row;
}

// Recent products
$result = $conn->query("
    SELECT id, name, series, price, stock 
    FROM products 
    ORDER BY id DESC 
    LIMIT 5
");
while ($row = $result->fetch_assoc()) {
    $stats['recent_products'][] = $row;
}

// Low stock products detail
$result = $conn->query("
    SELECT id, name, stock 
    FROM products 
    WHERE stock < 10 
    ORDER BY stock ASC 
    LIMIT 5
");
$stats['low_stock_products'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['low_stock_products'][] = $row;
}

sendJsonResponse(true, 'Statistics retrieved', $stats);
?>
