<?php
// View/Cart/index.php
// Variable: $cart (Cart)
?>
<h2>Your Cart</h2>

<?php if ($cart->isEmpty()): ?>
    <p>Your cart is empty.</p>
    <p><a href="/?url=product/index">Browse products</a></p>
<?php else: ?>
    <table border="1" cellpadding="6">
        <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
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
                    <form action="/?url=cart/update" method="post" style="display:inline">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item->product->id); ?>">
                        <input type="number" name="qty" value="<?php echo $item->qty; ?>" min="0">
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td><?php echo $item->product->priceFormatted(); ?></td>
                <td><?php echo number_format($item->subtotalCents() / 100, 2) . ' €'; ?></td>
                <td>
                    <form action="/?url=cart/remove" method="post" style="display:inline">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item->product->id); ?>">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" align="right"><strong>Total:</strong></td>
            <td colspan="2"><strong><?php echo $cart->total(); ?></strong></td>
        </tr>
        </tfoot>
    </table>

    <p><a href="/?url=checkout/index">Proceed to Checkout</a></p>
<?php endif; ?>