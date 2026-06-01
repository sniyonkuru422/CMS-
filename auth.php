<?php
session_start();

/**
 * Check if user is logged in
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.html");
        exit();
    }
}

/**
 * Check if user has required role
 */
function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: login.html");
        exit();
    }
}

// Function to check if user has any of the allowed roles (for pages that allow multiple roles)
function requireAnyRole($roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        header("Location: login.html");
        exit();
    }
}
?>