<div class="checkout-page">
  <h2>Thank you for your order!</h2>

  <p>Your order number is <strong><?= htmlspecialchars($orderNo); ?></strong>.</p>
  <p>Total (demo): <strong><?= htmlspecialchars($total); ?></strong></p>

  <h3>Shipping To</h3>
  <p><?= nl2br(htmlspecialchars($name . "\n" . $address)); ?><br>
  Email: <?= htmlspecialchars($email); ?></p>

  <h3>Items</h3>
  <ul>
    <?php foreach ($items as $item): ?>
      <li>
        <?= htmlspecialchars($item->product->name); ?> × <?= (int)$item->qty; ?> —
        <?= number_format($item->subtotalCents() / 100, 2) . ' €'; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <p><a class="btn ghost" href="/?url=home/index">Back to Home</a></p>
</div>