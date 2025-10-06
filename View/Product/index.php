<?php
// -----------------------------------------------------------------------------
// File: View/Product/index.php
// Project: Unkein E-Commerce Platform
// Purpose:
//   Displays the main product catalog page listing all available products.
//   Includes interactive price filtering powered by jQuery (see app.js).
//
// Context:
//   - Invoked by ProductController::index()
//   - Receives `$products`: an array of Product model instances
//   - Acts as a View layer only — contains no business logic
//
// Behavior:
//   - If `$products` is empty → shows a "No products available" message.
//   - Otherwise → renders a responsive grid of product cards.
//   - Each product card includes image, name, price, and details link.
//   - A dynamic filter bar allows users to filter products by price range.
//
// Layout:
//   - Wrapped inside `.container` for consistent horizontal padding.
//   - Uses `.card-grid` for flexible responsive layout (see _cards.scss).
//   - The `.filters` bar is styled for spacing and adaptive wrapping.
//
// Integration:
//   - Header and footer are handled automatically by BaseController::render().
//   - Filter functionality and animations are managed by public/js/app.js.
// -----------------------------------------------------------------------------
?>

<div class="container">

  <?php if (empty($products)): ?>
    <!-- CASE 1: No products found -->
    <p class="notice">No products available.</p>

  <?php else: ?>
    <!-- CASE 2: Products exist — display filter bar and grid -->

    <!-- PRICE FILTER BAR -->
    <!-- 
      Allows client-side filtering by price range:
        • All
        • Under 100 €
        • 100 € – 200 €
        • Over 200 €
      The active state is updated dynamically by jQuery.
    -->
    <div class="filters" style="margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap;">
      <button class="btn secondary active" data-price-filter="all">All</button>
      <button class="btn secondary" data-price-filter="lt100">Under 100 €</button>
      <button class="btn secondary" data-price-filter="100to200">100 € – 200 €</button>
      <button class="btn secondary" data-price-filter="gt200">Over 200 €</button>
    </div>

    <!-- PRODUCT GRID -->
    <!-- 
      Displays each product as a card with image, name, price, and a 
      “Details” button linking to ProductController::show($id).
    -->
    <div class="card-grid">
      <?php foreach ($products as $p): ?>
        <article class="card hover-glow">
          <div class="thumb">
            <img 
              src="<?= htmlspecialchars($p->image); ?>" 
              alt="<?= htmlspecialchars($p->name); ?>">
          </div>

          <div class="body">
            <div class="name"><?= htmlspecialchars($p->name); ?></div>
            <div class="meta">
              <span class="price"><?= $p->priceFormatted(); ?></span>
            </div>

            <div class="actions">
              <a 
                class="btn secondary" 
                href="/?url=product/show/<?= urlencode($p->id); ?>">
                Details
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

</div>