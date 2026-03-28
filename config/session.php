<?php
/**
 * Session Management
 * e-PHONE E-Commerce System
 */

session_start();

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        sendJsonResponse(false, 'Unauthorized. Please login.');
    }
}

/**
 * Require admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        sendJsonResponse(false, 'Access denied. Admin only.');
    }
}

/**
 * Set user session
 */
function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
}

/**
 * Clear user session
 */
function clearUserSession() {
    session_unset();
    session_destroy();
}
?>
