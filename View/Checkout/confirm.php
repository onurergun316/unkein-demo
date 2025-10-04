<?php
// -----------------------------------------------------------
// View/Checkout/confirm.php
// -----------------------------------------------------------
// PURPOSE:
//   This page is displayed immediately after a successful checkout.
//   It confirms to the customer that their order has been placed.
//
// DATA PROVIDED BY CONTROLLER (CheckoutController::placeOrder()):
//   $orderNo → Formatted order number (e.g. "UK000123")
//   $total   → Total order cost as formatted string ("39.99 €")
//   $items   → Array of CartItem objects (optional visual confirmation)
//   $name, $email, $address → Customer contact and shipping details
//
// ROLE IN BIG PICTURE:
//   - Represents the final step of the customer journey
//   - Provides reassurance and confirmation of order completion
//   - Demonstrates integration between checkout logic (model + controller)
//     and front-end presentation (view layer).
// -----------------------------------------------------------
?>

<h2>Thank you for your order!</h2>

<!-- Confirmation section -->
<p>
  Your order number is 
  <strong><?= htmlspecialchars($orderNo); ?></strong>.
</p>

<!-- Display total -->
<p>
  Total (demo): 
  <strong><?= htmlspecialchars($total); ?></strong>
</p>

<!-- Shipping address section -->
<h3>Shipping To</h3>
<p>
  <!-- Combine name and address into one block, preserving line breaks -->
  <?= nl2br(htmlspecialchars($name . "\n" . $address)); ?><br>
  Email: <?= htmlspecialchars($email); ?>
</p>

<!-- Item list section -->
<h3>Items</h3>
<ul>
  <?php foreach ($items as $item): ?>
    <li>
      <!-- Product name -->
      <?= htmlspecialchars($item->product->name); ?>
      <!-- Quantity -->
      × <?= (int)$item->qty; ?>
      <!-- Subtotal -->
      — <?= number_format($item->subtotalCents() / 100, 2) . ' €'; ?>
    </li>
  <?php endforeach; ?>
</ul>

<!-- Return link to homepage -->
<p>
  <a href="/?url=home/index">Back to Home</a>
</p>