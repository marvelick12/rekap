<?php
// Auth Helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is logged in.
 * If not, redirect to login page.
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Check if the user is already logged in.
 * If yes, redirect to dashboard.
 */
function require_guest() {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php?route=dashboard");
        exit;
    }
}

/**
 * Get current logged-in user id.
 */
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current logged-in user name.
 */
function get_user_name() {
    return $_SESSION['user_name'] ?? 'User';
}

/**
 * Get current logged-in user email.
 */
function get_user_email() {
    return $_SESSION['user_email'] ?? '';
}

/**
 * Get current logged-in user division.
 */
function get_user_division() {
    return $_SESSION['user_division'] ?? '';
}
