<?php
// View/Product/show.php
// Variable: $product (Product|null)
?>
<?php if (!$product): ?>
  <h2>Product not found</h2>
  <p><a class="btn ghost" href="/?url=product/index">Back to products</a></p>
<?php else: ?>
  <section class="product-detail">
    <div>
      <img src="<?php echo htmlspecialchars($product->image); ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
    </div>
    <div>
      <h2><?php echo htmlspecialchars($product->name); ?></h2>
      <p class="price"><?php echo $product->priceFormatted(); ?></p>
      <form action="/?url=cart/add" method="post">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->id); ?>">
        <div class="cart-actions">
          <div class="qty">
            <button class="btn secondary" type="button" data-step="dec">−</button>
            <input class="input" type="number" name="qty" value="1" min="1" step="1" style="width:80px">
            <button class="btn secondary" type="button" data-step="inc">+</button>
          </div>
          <button type="submit" class="btn" data-add-to-cart>Add to Cart</button>
        </div>
      </form>
      <p style="margin-top:12px;">
        <a class="btn ghost" href="/?url=product/index">Back to products</a>
      </p>
    </div>
  </section>
<?php endif; ?>