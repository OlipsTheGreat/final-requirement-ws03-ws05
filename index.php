<?php
// ============================================
// GADGET INVENTORY SYSTEM - Main Entry Point
// ============================================

// Include core configuration and helpers
require_once __DIR__ . '/core/config.php';

// Get the current page action
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) && $action !== 'login' && $action !== 'authenticate') {
    header('Location: ?action=login');
    exit;
}

// Route to the correct action processor or view
switch ($action) {

    // --- Authentication ---
    case 'login':
        if (isset($_SESSION['user_id'])) {
            header('Location: ?action=dashboard');
            exit;
        }
        include 'views/login.php';
        break;

    case 'authenticate':
    case 'logout':
        require 'actions/auth-process.php';
        break;

    // --- Dashboard ---
    case 'dashboard':
        include 'views/dashboard.php';
        break;

    // --- Items ---
    case 'items':
        include 'views/items/list.php';
        break;

    case 'items_add':
        include 'views/items/form.php';
        break;

    case 'items_edit':
        $item = find_by_id($conn, 'items', $_GET['id']);
        if (!$item || $_SESSION['role'] === 'regular') {
            header('Location: ?action=items');
            exit;
        }
        include 'views/items/form.php';
        break;

    case 'items_store':
    case 'items_update':
    case 'items_archive':
    case 'items_restore':
    case 'items_approve':
        require 'actions/item-process.php';
        break;

    // --- Users ---
    case 'users':
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=dashboard'); exit; }
        include 'views/users/list.php';
        break;

    case 'users_add':
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=dashboard'); exit; }
        include 'views/users/form.php';
        break;

    case 'users_store':
    case 'users_archive':
    case 'users_reset_password':
        require 'actions/user-process.php';
        break;

    default:
        echo "404 - Page not found.";
        break;
}

$conn->close();