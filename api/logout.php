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

sendJsonResponse(true, 'Logout successful');
?>
