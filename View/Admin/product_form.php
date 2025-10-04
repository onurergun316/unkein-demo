<?php require __DIR__ . '/../layout/header.php'; ?>

<?php
$editing = ($mode ?? '') === 'edit';
$p = $product ?? null; // may be null if creating

// Precomputed safe values (avoid accessing properties on null)
$nameVal  = ($editing && is_object($p)) ? htmlspecialchars($p->name) : '';
$priceVal = ($editing && is_object($p)) ? number_format($p->priceCents/100, 2, '.', '') : '';
$imageVal = ($editing && is_object($p) && !empty($p->image)) ? (string)$p->image : '';
?>

<h2><?= $editing ? 'Edit Product' : 'New Product' ?></h2>

<?php if (!empty($error)): ?>
  <p class="notice"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="/?url=admin/<?= $editing ? 'updateProduct' : 'createProduct' ?>" method="post" enctype="multipart/form-data" style="max-width:520px; display:grid; gap:12px;">
  <?php if ($editing && is_object($p)): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($p->id) ?>">
  <?php endif; ?>

  <div>
    <label>Name</label>
    <input class="input" type="text" name="name" value="<?= $nameVal ?>" required>
  </div>

  <div>
    <label>Price (€)</label>
    <input class="input" type="number" name="price" value="<?= $priceVal ?>" step="0.01" min="0.01" required>
  </div>

  <div>
    <label>Image (jpg/png/gif)</label>
    <?php if ($imageVal !== ''): ?>
      <div style="margin-bottom:6px;">
        <img src="<?= htmlspecialchars($imageVal) ?>" alt="" style="height:60px;">
      </div>
    <?php endif; ?>
    <input type="file" name="image" accept="image/*">
    <small>Leave empty to keep current image.</small>
  </div>

  <div style="display:flex; gap:8px;">
    <button class="btn" type="submit"><?= $editing ? 'Save Changes' : 'Create Product' ?></button>
    <a class="btn ghost" href="/?url=admin/products">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>