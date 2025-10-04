<?php require __DIR__ . '/../layout/header.php'; ?>
<?php
// -----------------------------------------------------------
// View/Admin/product_form.php
// -----------------------------------------------------------
// This view is shared by both "Create Product" and "Edit Product" pages
// in the Admin Dashboard.
//
// It is rendered by:
//   - AdminController::newProduct() → for creating
//   - AdminController::editProduct() → for editing
//
// The $mode variable tells us whether we're editing or creating.
// The $product object (if provided) contains the product’s data.
//
// -----------------------------------------------------------

// Determine if we are editing or creating a product
$editing = ($mode ?? '') === 'edit';

// Shortcut variable for readability
$p = $product ?? null; // may be null in create mode

// Precomputed safe values to fill in the form
// These prevent PHP errors if $p is null
$nameVal  = ($editing && is_object($p)) ? htmlspecialchars($p->name) : '';
$priceVal = ($editing && is_object($p)) ? number_format($p->priceCents / 100, 2, '.', '') : '';
$imageVal = ($editing && is_object($p) && !empty($p->image)) ? (string)$p->image : '';
?>

<!-- Title dynamically changes based on mode -->
<h2><?= $editing ? 'Edit Product' : 'New Product' ?></h2>

<!-- Display error messages if any -->
<?php if (!empty($error)): ?>
  <p class="notice"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- The product form -->
<form 
  action="/?url=admin/<?= $editing ? 'updateProduct' : 'createProduct' ?>" 
  method="post" 
  enctype="multipart/form-data"
  style="max-width:520px; display:grid; gap:12px;"
>

  <!-- Hidden field for product ID (only when editing) -->
  <?php if ($editing && is_object($p)): ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($p->id) ?>">
  <?php endif; ?>

  <!-- Product name -->
  <div>
    <label>Name</label>
    <input class="input" type="text" name="name" value="<?= $nameVal ?>" required>
  </div>

  <!-- Product price (stored in euros for readability) -->
  <div>
    <label>Price (€)</label>
    <input 
      class="input" 
      type="number" 
      name="price" 
      value="<?= $priceVal ?>" 
      step="0.01" 
      min="0.01" 
      required
    >
  </div>

  <!-- Product image upload section -->
  <div>
    <label>Image (jpg/png/gif)</label>

    <!-- If product already has an image, show a preview -->
    <?php if ($imageVal !== ''): ?>
      <div style="margin-bottom:6px;">
        <img src="<?= htmlspecialchars($imageVal) ?>" alt="" style="height:60px;">
      </div>
    <?php endif; ?>

    <!-- File input for new image -->
    <input type="file" name="image" accept="image/*">
    <small>Leave empty to keep current image.</small>
  </div>

  <!-- Submit and cancel buttons -->
  <div style="display:flex; gap:8px;">
    <button class="btn" type="submit">
      <?= $editing ? 'Save Changes' : 'Create Product' ?>
    </button>
    <a class="btn ghost" href="/?url=admin/products">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>