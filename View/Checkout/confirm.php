<?php
// View/Checkout/confirm.php
// Variables provided by controller:
// $orderNo (string), $total (string), $items (CartItem[]), $name (string), $email (string), $address (string)
?>

<h2>Thank you for your order!</h2>

<p>Your order number is <strong><?php echo htmlspecialchars($orderNo); ?></strong>.</p>
<p>Total (demo): <strong><?php echo htmlspecialchars($total); ?></strong></p>

<h3>Shipping To</h3>
<p>
    <?php echo nl2br(htmlspecialchars($name . "\n" . $address)); ?><br>
    Email: <?php echo htmlspecialchars($email); ?>
</p>

<h3>Items</h3>
<ul>
    <?php foreach ($items as $item): ?>
        <li>
            <?php echo htmlspecialchars($item->product->name); ?>
            × <?php echo (int)$item->qty; ?>
            — <?php echo number_format($item->subtotalCents() / 100, 2) . ' €'; ?>
        </li>
    <?php endforeach; ?>
</ul>

<p><a href="/?url=home/index">Back to Home</a></p>