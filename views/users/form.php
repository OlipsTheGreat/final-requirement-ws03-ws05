<?php ob_start(); ?>
<div class="header-action">
    <h1>Add New User</h1>
    <a href="?action=users" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back
    </a>
</div>

<div class="card form-card">
    <form action="?action=users_store" method="POST" class="form">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter a unique username" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter a strong password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <?php if ($_SESSION['role'] === 'super_admin'): ?>
                    <option value="admin">Admin</option>
                    <option value="regular">Regular User</option>
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <option value="regular">Regular User</option>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Create User</button>
    </form>
</div>
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layout.php'; 
?>
