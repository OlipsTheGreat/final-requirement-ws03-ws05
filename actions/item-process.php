<?php
if ($action === 'items_store') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);

        $id = generate_id();
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $quantity = (int) $_POST['quantity'];
        $created_by = $_SESSION['user_id'];
        $status = ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin') ? 'approved' : 'pending';

        $stmt = $conn->prepare("INSERT INTO items (id, name, description, quantity, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssiss', $id, $name, $description, $quantity, $status, $created_by);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Item added." . ($status === 'pending' ? " Waiting for admin approval." : "");
        header('Location: ?action=items');
        exit;
    }
} elseif ($action === 'items_update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=items'); exit; }

        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $quantity = (int) $_POST['quantity'];

        $stmt = $conn->prepare("UPDATE items SET name = ?, description = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param('ssis', $name, $description, $quantity, $id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Item updated.";
        header('Location: ?action=items');
        exit;
    }
} elseif ($action === 'items_archive') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=items'); exit; }
        update_status($conn, 'items', $_POST['id'], 'archived');
        $_SESSION['success'] = "Item archived.";
        header('Location: ?action=items');
        exit;
    }
} elseif ($action === 'items_restore') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=items'); exit; }
        update_status($conn, 'items', $_POST['id'], 'approved');
        $_SESSION['success'] = "Item restored.";
        header('Location: ?action=items');
        exit;
    }
} elseif ($action === 'items_approve') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        if ($_SESSION['role'] === 'regular') { header('Location: ?action=items'); exit; }
        update_status($conn, 'items', $_POST['id'], 'approved');
        $_SESSION['success'] = "Item approved.";
        header('Location: ?action=items');
        exit;
    }
}
