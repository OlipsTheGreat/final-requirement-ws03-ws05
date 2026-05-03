-- ============================================
-- GADGET INVENTORY SYSTEM
-- Paste this SQL into phpMyAdmin
-- ============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS gadget_inventory;
USE gadget_inventory;

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(16) NOT NULL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'regular') NOT NULL DEFAULT 'regular',
    status ENUM('active', 'archived') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ITEMS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS items (
    id VARCHAR(16) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    status ENUM('pending', 'approved', 'archived') NOT NULL DEFAULT 'pending',
    created_by VARCHAR(16) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DEFAULT SUPER ADMIN ACCOUNT
-- Username: superadmin
-- Password: password123
-- ============================================
INSERT INTO users (id, username, password_hash, role, status)
VALUES (
    'sa00000000000001',
    'superadmin',
    '$2y$10$Qj20t9p6pV8lJV.ns7U9u.mdf8wj/ctPHBXm5OMDV6tXCU40nrm0G',
    'super_admin',
    'active'
);
