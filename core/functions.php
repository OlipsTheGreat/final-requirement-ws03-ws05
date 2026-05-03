<?php
// ============================================
// Helper Functions
// ============================================

function generate_id() {
    return bin2hex(random_bytes(8));
}

function find_by_id($conn, $table, $id) {
    $allowed_tables = ['users', 'items'];
    if (!in_array($table, $allowed_tables)) return null;

    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ? LIMIT 1");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row;
}

function update_status($conn, $table, $id, $status) {
    $allowed_tables = ['users', 'items'];
    if (!in_array($table, $allowed_tables)) return false;

    $stmt = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $stmt->bind_param('ss', $status, $id);
    $stmt->execute();
    $stmt->close();
    return true;
}
