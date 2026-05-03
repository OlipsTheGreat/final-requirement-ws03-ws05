<?php
// Handle Search
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM items WHERE 1=1";
$types = "";
$params = [];

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $types .= "ss";
    $search_param = "%$search%";
    $params[] = &$search_param;
    $params[] = &$search_param;
}

if ($_SESSION['role'] === 'regular') {
    $sql .= " AND status = 'approved'";
}

$sql .= " ORDER BY name ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$filteredItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

ob_start();
?>
<div class="header-action">
    <h1>Items</h1>
    <a href="?action=items_add" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Item
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="search-form">
            <input type="hidden" name="action" value="items">
            <input type="text" name="q" placeholder="Search by name or description…" value="<?= escape($search) ?>" class="search-input">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Name</th><th>Description</th><th>Qty</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (empty($filteredItems)): ?>
                <tr><td colspan="5">
                    <div class="empty-state">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        <p>No items found</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($filteredItems as $item): ?>
                <tr>
                    <td style="color:var(--text-primary);font-weight:500"><?= escape($item['name']) ?></td>
                    <td><?= escape($item['description']) ?></td>
                    <td style="font-variant-numeric:tabular-nums"><?= (int)$item['quantity'] ?></td>
                    <td><span class="badge badge-<?= $item['status']==='approved'?'success':($item['status']==='pending'?'warning':'danger') ?>"><?= ucfirst($item['status']) ?></span></td>
                    <td>
                    <?php if ($_SESSION['role'] !== 'regular'): ?>
                        <div class="action-buttons-sm">
                        <?php if ($item['status']==='pending'): ?>
                            <form action="?action=items_approve" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>"><input type="hidden" name="id" value="<?= $item['id'] ?>"><button class="btn btn-sm btn-success">Approve</button></form>
                        <?php endif; ?>
                        <?php if ($item['status']!=='archived'): ?>
                            <a href="?action=items_edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="?action=items_archive" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>"><input type="hidden" name="id" value="<?= $item['id'] ?>"><button class="btn btn-sm btn-danger" onclick="return confirm('Archive this item?')">Archive</button></form>
                        <?php else: ?>
                            <form action="?action=items_restore" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>"><input type="hidden" name="id" value="<?= $item['id'] ?>"><button class="btn btn-sm btn-primary">Restore</button></form>
                        <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
