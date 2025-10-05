<?php
// View/Home/index.php
// Expects: $products (Product[])
require __DIR__ . '/../layout/header.php';
?>

<section class="home-hero">
  <div class="container">
    <p class="tag">fresh drops</p>
    <h2 class="headline">Clean basics for everyday</h2>
    <p class="sub">Minimal pieces, fair prices. Browse the latest picks below.</p>
    <a class="btn" href="/?url=product/index">Explore all products</a>
  </div>
</section>

<section class="home-grid container">
  <?php if (empty($products)): ?>
    <p class="notice">No products yet.</p>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach (array_slice($products, 0, 4) as $p): ?>
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
              <a class="btn secondary" href="/?url=product/show/<?= urlencode($p->id); ?>">View</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>