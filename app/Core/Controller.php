<?php

namespace App\Core;

class Controller {
    protected function render($view, $data = []) {
        // Extract data to make variables available in the view
        extract($data);
        
        // CSRF Token generation if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];

        // Define view path
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View $view not found at $viewPath");
        }
    }

    protected function redirect($action) {
        header("Location: index.php?action=" . $action);
        exit;
    }

    protected function verifyCsrf($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            die("CSRF token validation failed.");
        }
    }
}
