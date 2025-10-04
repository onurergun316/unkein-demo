<?php
// View/Checkout/index.php
// Variable: $cart (Cart)
?>
<h2>Checkout</h2>

<?php if ($cart->isEmpty()): ?>
    <p>Your cart is empty.</p>
    <p><a href="/?url=product/index">Browse products</a></p>
<?php else: ?>
    <p>Order total: <strong><?php echo $cart->total(); ?></strong></p>

    <form action="/?url=checkout/placeOrder" method="post">
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
        <button type="submit">Pay (Demo)</button>
    </form>
<?php endif; ?>