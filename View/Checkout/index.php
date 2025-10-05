<div class="checkout-page">
  <h2>Checkout</h2>

  <?php if ($cart->isEmpty()): ?>
      <p>Your cart is empty.</p>
      <p><a class="btn ghost" href="/?url=product/index">Browse products</a></p>

  <?php else: ?>
      <p>Order total: <strong><?= $cart->total(); ?></strong></p>

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