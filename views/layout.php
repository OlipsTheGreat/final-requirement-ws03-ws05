<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gadget Inventory System — A premium inventory management platform.">
    <title>Gadget Inventory System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <div class="nav-container">
            <a href="?action=dashboard" class="nav-brand">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -4px; margin-right: 6px; opacity: 0.8;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
                Inventory
            </a>
            <ul class="nav-links">
                <li><a href="?action=dashboard" class="<?= ($_GET['action'] ?? '') == 'dashboard' || ($_GET['action'] ?? '') == 'home' ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="?action=items" class="<?= strpos($_GET['action'] ?? '', 'items') !== false ? 'active' : '' ?>">Items</a></li>
                <?php if ($_SESSION['role'] !== 'regular'): ?>
                    <li><a href="?action=users" class="<?= strpos($_GET['action'] ?? '', 'users') !== false ? 'active' : '' ?>">Users</a></li>
                <?php endif; ?>
                <li><a href="?action=logout" class="nav-logout">Logout</a></li>
            </ul>
        </div>
    </nav>
<?php endif; ?>

<main class="container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= escape($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= escape($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php echo $content; ?>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> Gadget Inventory System</p>
</footer>

</body>
</html>
