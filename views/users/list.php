<?php
// Get users directly
$sql = "SELECT * FROM users WHERE status != 'archived'";
if ($_SESSION['role'] === 'admin') {
    $sql .= " AND role = 'regular'";
}
$result = $conn->query($sql);
$activeUsers = $result->fetch_all(MYSQLI_ASSOC);

ob_start();
?>
<div class="header-action">
    <h1>Manage Users</h1>
    <?php if ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin'): ?>
    <a href="?action=users_add" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add User
    </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Username</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (empty($activeUsers)): ?>
                <tr><td colspan="4">
                    <div class="empty-state">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <p>No active users found</p>
                    </div>
                </td></tr>
                <?php else: ?>
                <?php foreach ($activeUsers as $user): ?>
                <tr>
                    <td style="color:var(--text-primary);font-weight:500">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="margin-right:6px"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <?= escape($user['username']) ?>
                    </td>
                    <td><span class="badge badge-default"><?= strtoupper(str_replace('_',' ',$user['role'])) ?></span></td>
                    <td><span class="badge badge-success"><?= strtoupper($user['status']) ?></span></td>
                    <td>
                        <?php if ($user['id'] !== $_SESSION['user_id'] && ($_SESSION['role'] === 'super_admin' || ($_SESSION['role'] === 'admin' && $user['role'] === 'regular'))): ?>
                            <div class="action-buttons-sm">
                                <form action="?action=users_reset_password" method="POST" style="display:inline">
                                    <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="new_password" value="Password123!">
                                    <button class="btn btn-sm btn-secondary" onclick="return confirm('Reset password to Password123!?')">Reset Password</button>
                                </form>
                                <form action="?action=users_archive" method="POST" style="display:inline">
                                    <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Archive user <?= escape($user['username']) ?>?')">Archive</button>
                                </form>
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
