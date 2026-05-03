<?php ob_start(); ?>
<div class="login-wrapper">
    <div class="card login-card">
        <div class="card-header">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="url(#g)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:14px">
                <defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#0a84ff"/><stop offset="100%" stop-color="#bf5af2"/></linearGradient></defs>
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
            </svg>
            <h2>Welcome Back</h2>
            <p>Sign in to manage your inventory</p>
        </div>
        <form action="?action=authenticate" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
