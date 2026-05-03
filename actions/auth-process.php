<?php
if ($action === 'authenticate') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf($_POST['csrf_token']);

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND status = 'active' LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ?action=dashboard');
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header('Location: ?action=login');
        }
        exit;
    }
} elseif ($action === 'logout') {
    session_destroy();
    header('Location: ?action=login');
    exit;
}
