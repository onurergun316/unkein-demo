<?php
// -----------------------------------------------------------------------------
// File: View/Product/index.php
// Project: Unkein E-Commerce Platform
// Purpose: Displays the product listing page showing all available products.
//
// Context:
//   - Invoked by ProductController::index().
//   - Renders a grid of product cards (image, name, price, and details link).
//   - Expects `$products` — an array of Product model instances.
//
// Behavior:
//   - If `$products` is empty → shows a notice message.
//   - Otherwise → loops through all products and generates a responsive grid.
//
// Layout:
//   - Wrapped in a `.container` for horizontal padding and alignment.
//   - Uses `.card-grid` and `.card` styles (defined in _cards.scss).
//   - Each product card includes image, metadata, and action button.
//
// Integration:
//   - No header or footer included directly — BaseController::render() handles layout.
// -----------------------------------------------------------------------------
?>
<div class="container">
  <h2 class="section-title">Products</h2>

  <?php if (empty($products)): ?>
    <p class="notice">No products available.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($products as $p): ?>
        <article class="card hover-glow">
          <div class="thumb">
            <img src="<?= htmlspecialchars($p->image); ?>" alt="<?= htmlspecialchars($p->name); ?>">
          </div>
          <div class="body">
            <div class="name"><?= htmlspecialchars($p->name); ?></div>
            <div class="meta">
              <span class="price"><?= $p->priceFormatted(); ?></span>
            </div>
            <div class="actions">
              <a class="btn secondary" href="/?url=product/show/<?= urlencode($p->id); ?>">Details</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>