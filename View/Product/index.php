<?php
// View/Product/index.php
// Variables: $products (Product[])
// NOTE: No header/footer includes here—BaseController::render() wraps this.
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