<?php

namespace App\Models;

use App\Core\Database;

class Item {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById($id) {
        $result = $this->db->query("SELECT * FROM items WHERE id = ? LIMIT 1", [$id]);
        return $result->fetch_assoc();
    }

    public function all($status = null) {
        $sql = "SELECT i.*, u.username as creator FROM items i LEFT JOIN users u ON i.created_by = u.id";
        if ($status) {
            $sql .= " WHERE i.status = ?";
            return $this->db->query($sql, [$status]);
        }
        $sql .= " ORDER BY i.created_at DESC";
        return $this->db->query($sql);
    }

    public function create($data) {
        $sql = "INSERT INTO items (id, name, description, quantity, status, created_by) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->db->query($sql, [
            $data['id'],
            $data['name'],
            $data['description'],
            $data['quantity'],
            $data['status'],
            $data['created_by']
        ], "ssisss");
    }

    public function update($id, $data) {
        $sql = "UPDATE items SET name = ?, description = ?, quantity = ? WHERE id = ?";
        return $this->db->query($sql, [
            $data['name'],
            $data['description'],
            $data['quantity'],
            $id
        ], "ssis");
    }

    public function updateStatus($id, $status) {
        return $this->db->query("UPDATE items SET status = ? WHERE id = ?", [$status, $id]);
    }
}
