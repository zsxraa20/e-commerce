<?php
/**
 * Checkout API
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
$required = ['items', 'customer_name', 'customer_phone', 'customer_address', 'payment_method'];
foreach ($required as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        sendJsonResponse(false, "Field '$field' is required");
    }
}

$items = $data['items'];
$customerName = sanitizeInput($conn, $data['customer_name']);
$customerPhone = sanitizeInput($conn, $data['customer_phone']);
$customerAddress = sanitizeInput($conn, $data['customer_address']);
$paymentMethod = sanitizeInput($conn, $data['payment_method']);
$additionalNotes = isset($data['additional_notes']) ? sanitizeInput($conn, $data['additional_notes']) : '';
$paymentProof = isset($data['payment_proof']) ? sanitizeInput($conn, $data['payment_proof']) : '';

// Validate items
if (!is_array($items) || count($items) === 0) {
    sendJsonResponse(false, 'Cart is empty');
}

// Calculate total and validate products
$totalAmount = 0;
$validatedItems = [];

foreach ($items as $item) {
    if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['price'])) {
        sendJsonResponse(false, 'Invalid item data');
    }
    
    $productId = intval($item['product_id']);
    $quantity = intval($item['quantity']);
    $price = floatval($item['price']);
    $colorName = isset($item['color_name']) ? sanitizeInput($conn, $item['color_name']) : '';
    
    // Verify product exists and has stock (product-level + color-level)
    $stmt = $conn->prepare("SELECT p.name, p.stock, pc.stock AS color_stock
        FROM products p
        LEFT JOIN product_colors pc ON pc.product_id = p.id AND pc.color_name = ?
        WHERE p.id = ? AND p.status = 'active'
        LIMIT 1");
    $stmt->bind_param("si", $colorName, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendJsonResponse(false, "Product ID $productId not found or inactive");
    }

    $product = $result->fetch_assoc();

    if ($product['stock'] < $quantity) {
        sendJsonResponse(false, "Insufficient stock for {$product['name']}");
    }

    if (!empty($colorName)) {
        if ($product['color_stock'] === null) {
            sendJsonResponse(false, "Varian warna '$colorName' tidak ditemukan untuk {$product['name']}");
        }

        if (intval($product['color_stock']) < $quantity) {
            sendJsonResponse(false, "Insufficient stock varian $colorName untuk {$product['name']}");
        }
    }
    
    $itemTotal = $price * $quantity;
    $totalAmount += $itemTotal;
    
    $validatedItems[] = [
        'product_id' => $productId,
        'product_name' => $product['name'],
        'color_name' => $colorName,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// Start transaction
$conn->begin_transaction();

try {
    // Get user ID if logged in
    $userId = null;
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    }
    
    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions 
        (user_id, customer_name, customer_phone, customer_address, payment_method, payment_proof, additional_notes, total_amount, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issssssd", $userId, $customerName, $customerPhone, $customerAddress, $paymentMethod, $paymentProof, $additionalNotes, $totalAmount);
    $stmt->execute();
    
    $transactionId = $stmt->insert_id;
    
    // Insert transaction items and update stock
    $stmt = $conn->prepare("INSERT INTO transaction_items 
        (transaction_id, product_id, product_name, color_name, price, quantity) 
        VALUES (?, ?, ?, ?, ?, ?)");
    
    $updateStockStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $updateColorStockStmt = $conn->prepare("UPDATE product_colors SET stock = stock - ? WHERE product_id = ? AND color_name = ?");
    
    foreach ($validatedItems as $item) {
        // Insert item
        $stmt->bind_param("iissdi", $transactionId, $item['product_id'], $item['product_name'], $item['color_name'], $item['price'], $item['quantity']);
        $stmt->execute();
        
        // Update product stock
        $updateStockStmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $updateStockStmt->execute();

        // Update color stock (if color variant exists)
        if (!empty($item['color_name'])) {
            $updateColorStockStmt->bind_param("iis", $item['quantity'], $item['product_id'], $item['color_name']);
            $updateColorStockStmt->execute();
        }
    }
    
    // Add order history
    $historyStmt = $conn->prepare("INSERT INTO order_history (transaction_id, status, notes) VALUES (?, 'pending', 'Order created')");
    $historyStmt->bind_param("i", $transactionId);
    $historyStmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    sendJsonResponse(true, 'Order placed successfully', [
        'transaction_id' => $transactionId,
        'total_amount' => $totalAmount,
        'status' => 'pending'
    ]);
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    sendJsonResponse(false, 'Failed to process order: ' . $e->getMessage());
}
?>
