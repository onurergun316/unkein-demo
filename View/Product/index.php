<?php
// View/Product/index.php
// Variables: $products (Product[])
?>
<h2>Products</h2>
<div class="card-grid">
  <?php foreach ($products as $p): ?>
    <article class="card">
      <div class="thumb">
        <img src="<?php echo htmlspecialchars($p->image); ?>" alt="<?php echo htmlspecialchars($p->name); ?>">
      </div>
      <div class="body">
        <div class="name"><?php echo htmlspecialchars($p->name); ?></div>
        <div class="price"><?php echo $p->priceFormatted(); ?></div>
        <div>
          <a class="btn secondary" href="/?url=product/show/<?php echo urlencode($p->id); ?>">Details</a>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
</div>