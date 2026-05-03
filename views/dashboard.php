<?php
// Get counts directly
$result = $conn->query("SELECT status, COUNT(*) as count FROM items GROUP BY status");
$item_counts = ['approved' => 0, 'pending' => 0, 'archived' => 0];
while($row = $result->fetch_assoc()) {
    $item_counts[$row['status']] = $row['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
$activeUsers = $result->fetch_assoc()['count'];

ob_start();
?>
<div class="header-action">
    <div>
        <h1>Dashboard</h1>
        <p style="color:var(--text-tertiary);margin-top:2px;font-size:.9rem">Welcome, <span style="color:var(--text-secondary)"><?= escape($_SESSION['username']) ?></span> · <?= ucfirst(str_replace('_',' ',$_SESSION['role'])) ?></p>
    </div>
</div>

<div class="grid">
    <div class="card stat-card">
        <h3>Approved Items</h3>
        <p class="stat-value"><?= $item_counts['approved'] ?></p>
    </div>
    <?php if ($_SESSION['role'] !== 'regular'): ?>
    <div class="card stat-card">
        <h3>Pending</h3>
        <p class="stat-value stat-value-orange"><?= $item_counts['pending'] ?></p>
    </div>
    <div class="card stat-card">
        <h3>Archived</h3>
        <p class="stat-value stat-value-muted"><?= $item_counts['archived'] ?></p>
    </div>
    <div class="card stat-card">
        <h3>Active Users</h3>
        <p class="stat-value stat-value-green"><?= $activeUsers ?></p>
    </div>
    <?php endif; ?>
</div>

<div class="card mt-4">
    <div class="card-header"><h2>Quick Actions</h2></div>
    <div class="card-body">
        <div class="action-buttons">
            <a href="?action=items" class="btn btn-secondary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View Items
            </a>
            <a href="?action=items_add" class="btn btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Item
            </a>
            <?php if ($_SESSION['role'] !== 'regular'): ?>
            <a href="?action=users" class="btn btn-secondary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Manage Users
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
