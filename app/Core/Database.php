<?php

namespace App\Core;

use mysqli;
use Exception;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        
        $this->connection = new mysqli(
            $config['host'],
            $config['user'],
            $config['pass'],
            $config['name']
        );

        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset($config['charset']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Helper for prepared statements
    public function query($sql, $params = [], $types = "") {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = str_repeat('s', count($params));
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        
        // If it's a SELECT query, return result
        if (stripos($sql, 'SELECT') === 0) {
            return $result;
        }

        // For INSERT/UPDATE/DELETE
        return $stmt;
    }
}
