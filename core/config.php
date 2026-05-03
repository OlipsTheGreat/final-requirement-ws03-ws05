<?php
// ============================================
// Core Configuration
// ============================================

session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'gadget_inventory');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Include helper files
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/functions.php';
