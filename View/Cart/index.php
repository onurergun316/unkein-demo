<?php
// View/Cart/index.php
// Variable: $cart (Cart)
?>
<h2>Your Cart</h2>

<?php if ($cart->isEmpty()): ?>
  <p class="notice">Your cart is empty.</p>
  <p><a class="btn ghost" href="/?url=product/index">Browse products</a></p>
<?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Product</th>
          <th style="width:180px;">Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cart->items() as $item): ?>
        <tr>
          <td><?php echo htmlspecialchars($item->product->name); ?></td>
          <td>
            <form action="/?url=cart/update" method="post" style="display:flex; gap:6px; align-items:center;">
              <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item->product->id); ?>">
              <button class="btn secondary" type="button" data-step="dec">−</button>
              <input class="input" type="number" name="qty" value="<?php echo (int)$item->qty; ?>" min="0" step="1" style="width:80px">
              <button class="btn secondary" type="button" data-step="inc">+</button>
              <button class="btn" type="submit">Update</button>
            </form>
          </td>
          <td><?php echo $item->product->priceFormatted(); ?></td>
          <td><?php echo number_format($item->subtotalCents() / 100, 2) . ' €'; ?></td>
          <td>
            <form action="/?url=cart/remove" method="post" style="display:inline">
              <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item->product->id); ?>">
              <button class="btn ghost" type="submit">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
          <td colspan="2"><strong><?php echo $cart->total(); ?></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <p style="margin-top:12px;">
    <a class="btn" href="/?url=checkout/index">Proceed to Checkout</a>
  </p>
<?php endif; ?>