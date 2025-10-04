<?php
// -----------------------------------------------------------
// View/Product/show.php
// -----------------------------------------------------------
// PURPOSE:
//   Displays details for a single product. 
//   Includes an "Add to Cart" form and graceful handling 
//   for when the product cannot be found.
//
// DATA PROVIDED BY CONTROLLER:
//   $product — a Product object (or null if not found)
//   (passed from ProductController::show($id))
//
// ROLE IN MVC:
//   - Acts purely as a View layer.
//   - Shows product info, price, and image.
//   - Allows quantity selection and adding to cart.
//   - Contains no database or logic beyond presentation.
// -----------------------------------------------------------
?>

<?php if (!$product): ?>
  <!-- Product not found view -->
  <h2>Product not found</h2>
  <p><a class="btn ghost" href="/?url=product/index">Back to products</a></p>

<?php else: ?>
  <!-- Product details view -->
  <section class="product-detail">
    <!-- Product image -->
    <div>
      <img 
        src="<?php echo htmlspecialchars($product->image); ?>" 
        alt="<?php echo htmlspecialchars($product->name); ?>">
    </div>

    <!-- Product information -->
    <div>
      <h2><?php echo htmlspecialchars($product->name); ?></h2>
      <p class="price"><?php echo $product->priceFormatted(); ?></p>

      <!-- Add to Cart form -->
      <form action="/?url=cart/add" method="post">
        <input 
          type="hidden" 
          name="product_id" 
          value="<?php echo htmlspecialchars($product->id); ?>">

        <div class="cart-actions">
          <!-- Quantity control -->
          <div class="qty">
            <button class="btn secondary" type="button" data-step="dec">−</button>
            <input 
              class="input" 
              type="number" 
              name="qty" 
              value="1" 
              min="1" 
              step="1" 
              style="width:80px">
            <button class="btn secondary" type="button" data-step="inc">+</button>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn" data-add-to-cart>Add to Cart</button>
        </div>
      </form>

      <!-- Navigation link -->
      <p style="margin-top:12px;">
        <a class="btn ghost" href="/?url=product/index">Back to products</a>
      </p>
    </div>
  </section>
<?php endif; ?>