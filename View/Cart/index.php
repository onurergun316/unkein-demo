<?php
// -----------------------------------------------------------
// View/Cart/index.php
// -----------------------------------------------------------
// PURPOSE:
//   Displays the user's current shopping cart. 
//   It shows the list of items, quantities, subtotals, and total price.
//   It also provides buttons to update quantities, remove items, or proceed to checkout.
//
// DATA AVAILABLE:
//   $cart → instance of Cart class (passed from CartController::index())
//
// BEHAVIOR:
//   - If the cart is empty → shows a message and a link to browse products
//   - If not empty → displays a table of all items
//   - Each item can be updated or removed individually
//   - The bottom of the page shows total cost and checkout button
// -----------------------------------------------------------
?>

<h2>Your Cart</h2>

<?php if ($cart->isEmpty()): ?>
  <!-- CASE 1: Cart has no items -->
  <p class="notice">Your cart is empty.</p>
  <p>
    <!-- Redirects back to product listing -->
    <a class="btn ghost" href="/?url=product/index">Browse products</a>
  </p>

<?php else: ?>
  <!-- CASE 2: Cart has one or more items -->
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
        <!-- Each CartItem corresponds to a row -->
        <tr>
          <!-- PRODUCT NAME -->
          <td><?= htmlspecialchars($item->product->name); ?></td>

          <!-- QUANTITY FORM -->
          <td>
            <!-- Form posts to CartController::update() -->
            <form 
              action="/?url=cart/update" 
              method="post" 
              style="display:flex; gap:6px; align-items:center;"
            >
              <!-- Hidden field keeps product reference -->
              <input 
                type="hidden" 
                name="product_id" 
                value="<?= htmlspecialchars($item->product->id); ?>"
              >

              <!-- Decrease quantity button (handled via JS or manual change) -->
              <button class="btn secondary" type="button" data-step="dec">−</button>

              <!-- Quantity input -->
              <input 
                class="input" 
                type="number" 
                name="qty" 
                value="<?= (int)$item->qty; ?>" 
                min="0" 
                step="1" 
                style="width:80px"
              >

              <!-- Increase quantity button -->
              <button class="btn secondary" type="button" data-step="inc">+</button>

              <!-- Update button submits the form -->
              <button class="btn" type="submit">Update</button>
            </form>
          </td>

          <!-- UNIT PRICE (via Product::priceFormatted) -->
          <td><?= $item->product->priceFormatted(); ?></td>

          <!-- SUBTOTAL (quantity * unit price) -->
          <td><?= number_format($item->subtotalCents() / 100, 2) . ' €'; ?></td>

          <!-- REMOVE ITEM FORM -->
          <td>
            <!-- Posts to CartController::remove() -->
            <form 
              action="/?url=cart/remove" 
              method="post" 
              style="display:inline"
            >
              <input 
                type="hidden" 
                name="product_id" 
                value="<?= htmlspecialchars($item->product->id); ?>"
              >
              <button class="btn ghost" type="submit">Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>

      <!-- CART TOTAL -->
      <tfoot>
        <tr>
          <!-- Left cells blank -->
          <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
          <td colspan="2"><strong><?= $cart->total(); ?></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- Checkout button -->
  <p style="margin-top:12px;">
    <a class="btn" href="/?url=checkout/index">Proceed to Checkout</a>
  </p>
<?php endif; ?>