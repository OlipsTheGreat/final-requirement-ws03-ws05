<?php 
$isEdit = isset($item);
ob_start(); 
?>
<div class="header-action">
    <h1><?= $isEdit ? 'Edit Item' : 'Add New Item' ?></h1>
    <a href="?action=items" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back
    </a>
</div>

<div class="card form-card">
    <form action="<?= $isEdit ? '?action=items_update' : '?action=items_store' ?>" method="POST" class="form">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" id="name" name="name" placeholder="e.g. MacBook Pro 16-inch" value="<?= $isEdit ? escape($item['name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Brief description of the gadget…" required><?= $isEdit ? escape($item['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?= $isEdit ? (int)$item['quantity'] : '1' ?>" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full"><?= $isEdit ? 'Save Changes' : 'Submit Item' ?></button>
    </form>
</div>
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layout.php'; 
?>
