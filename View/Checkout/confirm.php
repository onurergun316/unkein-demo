<?php
// -----------------------------------------------------------------------------
// File: View/Checkout/confirm.php
// Project: Unkein E-Commerce Platform
// Purpose: Displays the order confirmation page after successful checkout.
//
// Context:
//   - Rendered by CheckoutController::placeOrder()
//   - Confirms order number, total cost, shipping details, and purchased items.
//
// Data Passed In:
//   $orderNo  → string   (e.g., "UK000123")
//   $total    → string   (formatted total, e.g., "39.99 €")
//   $items    → CartItem[] (objects containing product + quantity info)
//   $name     → string   (customer name)
//   $email    → string   (customer email address)
//   $address  → string   (shipping address)
//
// Notes:
//   - The `.checkout-page` class ensures consistent horizontal padding
//     with the rest of the checkout views.
// -----------------------------------------------------------------------------
?>

<div class="checkout-page">
  <h2>Thank you for your order!</h2>

  <!-- Basic order summary -->
  <p>Your order number is <strong><?= htmlspecialchars($orderNo); ?></strong>.</p>
  <p>Total (demo): <strong><?= htmlspecialchars($total); ?></strong></p>

  <!-- Customer shipping and contact information -->
  <h3>Shipping To</h3>
  <p>
    <?= nl2br(htmlspecialchars($name . "\n" . $address)); ?><br>
    Email: <?= htmlspecialchars($email); ?>
  </p>

  <!-- Ordered items summary -->
  <h3>Items</h3>
  <ul>
    <?php foreach ($items as $item): ?>
      <li>
        <?= htmlspecialchars($item->product->name); ?> × <?= (int)$item->qty; ?> —
        <?= number_format($item->subtotalCents() / 100, 2) . ' €'; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Navigation back to homepage -->
  <p><a class="btn ghost" href="/?url=home/index">Back to Home</a></p>
</div>