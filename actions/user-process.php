<?php
if ($action === 'users_store') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);

        $role_to_create = $_POST['role'];

        // Permission check
        if ($_SESSION['role'] === 'admin' && $role_to_create !== 'regular') {
            $_SESSION['error'] = "You can only create regular users.";
            header('Location: ?action=users');
            exit;
        }

        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Username already exists.";
            header('Location: ?action=users_add');
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Create user
        $id = generate_id();
        $username = trim($_POST['username']);
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $status = 'active';

        $stmt = $conn->prepare("INSERT INTO users (id, username, password_hash, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $id, $username, $password_hash, $role_to_create, $status);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "User created.";
        header('Location: ?action=users');
        exit;
    }
} elseif ($action === 'users_archive') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        $target = find_by_id($conn, 'users', $_POST['id']);

        if (!$target || $target['id'] === $_SESSION['user_id']) {
            $_SESSION['error'] = "Cannot archive this user.";
        } elseif ($_SESSION['role'] === 'admin' && $target['role'] !== 'regular') {
            $_SESSION['error'] = "You can only archive regular users.";
        } else {
            update_status($conn, 'users', $_POST['id'], 'archived');
            $_SESSION['success'] = "User archived.";
        }
        header('Location: ?action=users');
        exit;
    }
} elseif ($action === 'users_reset_password') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);
        $target = find_by_id($conn, 'users', $_POST['id']);

        if (!$target) {
            $_SESSION['error'] = "User not found.";
        } elseif ($_SESSION['role'] === 'admin' && $target['role'] !== 'regular') {
            $_SESSION['error'] = "You can only reset regular user passwords.";
        } else {
            $new_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->bind_param('ss', $new_hash, $_POST['id']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success'] = "Password reset.";
        }
        header('Location: ?action=users');
        exit;
    }
}
