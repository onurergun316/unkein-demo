<?php // header/footer are injected by BaseController::render() ?>
<?php
// -----------------------------------------------------------
// View/Admin/products.php
// -----------------------------------------------------------
// This view renders the "Manage Products" page in the Admin Dashboard.
// It displays a list of all active products with options to:
//   • Create a new product
//   • Edit existing products
//   • Delete products (soft delete)
// -----------------------------------------------------------
// Data passed from AdminController::products():
//   $products → array of Product objects
// -----------------------------------------------------------
?>

<!-- Page Title -->
<h2>Products</h2>

<!-- Navigation Buttons -->
<p>
  <!-- Button to go to 'New Product' form -->
  <a class="btn" href="/?url=admin/newProduct">+ New Product</a>

  <!-- Button to return to Admin Dashboard -->
  <a class="btn ghost" href="/?url=admin/dashboard">Back to Dashboard</a>
</p>

<!-- Products Table -->
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Price</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <!-- Product ID -->
        <td><?= htmlspecialchars($p->id) ?></td>

        <!-- Product Name -->
        <td><?= htmlspecialchars($p->name) ?></td>

        <!-- Product Price (formatted by Product::priceFormatted()) -->
        <td><?= $p->priceFormatted() ?></td>

        <!-- Product Image Thumbnail -->
        <td>
          <?php if ($p->image): ?>
            <img 
              src="<?= htmlspecialchars($p->image) ?>" 
              alt="" 
              style="height:40px;"
            >
          <?php endif; ?>
        </td>

        <!-- Admin Actions: Edit / Delete -->
        <td style="display:flex; gap:8px;">
          <!-- Edit Button links to editProduct/{id} -->
          <a 
            class="btn secondary" 
            href="/?url=admin/editProduct/<?= urlencode($p->id) ?>"
          >
            Edit
          </a>

          <!-- Delete Form (POST) -->
          <!-- Uses a confirmation dialog before deleting -->
          <form 
            action="/?url=admin/deleteProduct" 
            method="post" 
            onsubmit="return confirm('Delete product?');"
          >
            <!-- Hidden field carrying the product ID -->
            <input 
              type="hidden" 
              name="id" 
              value="<?= htmlspecialchars($p->id) ?>"
            >
            <!-- Delete Button -->
            <button class="btn ghost" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>