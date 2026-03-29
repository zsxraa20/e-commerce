<?php
/**
 * Logout API
 * e-PHONE E-Commerce System
 */

require_once '../config/session.php';

// Set JSON header
header('Content-Type: application/json');

// Clear session
clearUserSession();

// Return JSON response tanpa bergantung ke helper DB
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Logout successful'
]);
exit;
?>
