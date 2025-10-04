<?php
// View/Product/show.php
// Variable: $product (Product|null)
?>
<?php if (!$product): ?>
    <h2>Product not found</h2>
    <p><a href="/?url=product/index">Back to products</a></p>
<?php else: ?>
    <h2><?php echo htmlspecialchars($product->name); ?></h2>
    <p>Price: <?php echo $product->priceFormatted(); ?></p>
    <p>Category: <?php echo htmlspecialchars($product->category); ?></p>

    <form action="/?url=cart/add" method="post">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->id); ?>">
        <label>
            Qty:
            <input type="number" name="qty" value="1" min="1" step="1">
        </label>
        <button type="submit">Add to Cart</button>
    </form>

    <p><a href="/?url=product/index">Back to products</a></p>
<?php endif; ?>