<?php

namespace App\Models;

use App\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById($id) {
        $result = $this->db->query("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
        return $result->fetch_assoc();
    }

    public function findByUsername($username) {
        $result = $this->db->query("SELECT * FROM users WHERE username = ? LIMIT 1", [$username]);
        return $result->fetch_assoc();
    }

    public function all() {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
    }

    public function create($data) {
        $sql = "INSERT INTO users (id, username, password, role, status) VALUES (?, ?, ?, ?, ?)";
        return $this->db->query($sql, [
            $data['id'],
            $data['username'],
            $data['password'],
            $data['role'],
            $data['status']
        ]);
    }

    public function updateStatus($id, $status) {
        return $this->db->query("UPDATE users SET status = ? WHERE id = ?", [$status, $id]);
    }
}
