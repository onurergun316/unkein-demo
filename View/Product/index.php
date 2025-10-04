<?php
// -----------------------------------------------------------
// View/Product/index.php
// -----------------------------------------------------------
// PURPOSE:
//   Displays all available products in a grid layout.
//   Each product card includes an image, name, price,
//   and a link to its detailed page.
//
// DATA PROVIDED BY CONTROLLER:
//   $products — an array of Product objects
//   (fetched via ProductRepository::all() in ProductController::index())
//
// ROLE IN MVC:
//   - Acts as the View layer for listing products.
//   - Receives clean data from the ProductController.
//   - Contains no database logic — purely presentation.
// -----------------------------------------------------------
?>

<h2>Products</h2>

<div class="card-grid">
  <?php foreach ($products as $p): ?>
    <article class="card">
      <!-- Product image -->
      <div class="thumb">
        <img 
          src="<?php echo htmlspecialchars($p->image); ?>" 
          alt="<?php echo htmlspecialchars($p->name); ?>">
      </div>

      <!-- Product details -->
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