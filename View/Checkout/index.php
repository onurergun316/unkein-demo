<?php
// -----------------------------------------------------------------------------
// File: View/Checkout/index.php
// Project: Unkein E-Commerce Platform
// Purpose: Displays the checkout form where the user enters name, email,
//          and shipping address before finalizing their order.
//
// Context:
//   - Rendered by CheckoutController::index()
//   - Collects customer details and posts them to CheckoutController::placeOrder()
//
// Data Passed In:
//   $cart → instance of Cart (contains CartItem objects)
//
// Behavior:
//   - If the cart is empty → show a notice and link back to products
//   - Otherwise → show the order total and input fields for user details
//
// Styling:
//   - Uses `.checkout-page` for layout alignment
//   - Uses `.checkout-form` for consistent spacing and input appearance
// -----------------------------------------------------------------------------
?>

<div class="checkout-page">
  <h2>Checkout</h2>

  <?php if ($cart->isEmpty()): ?>
      <!-- CASE: No items in cart -->
      <p>Your cart is empty.</p>
      <p><a class="btn ghost" href="/?url=product/index">Browse products</a></p>

  <?php else: ?>
      <!-- CASE: Cart contains items -->
      <p>Order total: <strong><?= $cart->total(); ?></strong></p>

      <!-- Checkout form -->
      <form action="/?url=checkout/placeOrder" method="post" class="checkout-form">
          <div>
              <label>Name:<br>
                  <input type="text" name="name" required>
              </label>
          </div>

          <div>
              <label>Email:<br>
                  <input type="email" name="email" required>
              </label>
          </div>

          <div>
              <label>Address:<br>
                  <textarea name="address" required></textarea>
              </label>
          </div>

          <button class="btn" type="submit">Pay (Demo)</button>
      </form>
  <?php endif; ?>
</div>