<?php
// -----------------------------------------------------------
// View/Checkout/index.php
// -----------------------------------------------------------
// PURPOSE:
//   Displays the checkout page where the user provides their
//   name, email, and shipping address to place an order.
//
// DATA PROVIDED BY CONTROLLER:
//   $cart — an instance of the Cart class containing CartItem objects.
//
// ROLE IN BIG PICTURE:
//   - Represents the customer-facing checkout form.
//   - Bridges the shopping cart (CartController) with order placement
//     logic (CheckoutController + OrderRepository).
//   - Shows order total and collects shipping + contact info.
//
// SECURITY / FUNCTIONALITY NOTES:
//   - Uses server-side validation (via CheckoutController).
//   - No client-side JavaScript required — simple, reliable form flow.
// -----------------------------------------------------------
?>

<h2>Checkout</h2>

<?php if ($cart->isEmpty()): ?>
    <!-- If the cart is empty, show message and a link to products -->
    <p>Your cart is empty.</p>
    <p><a href="/?url=product/index">Browse products</a></p>

<?php else: ?>
    <!-- Display total order price (Cart::total() returns formatted string) -->
    <p>Order total: <strong><?= $cart->total(); ?></strong></p>

    <!-- Checkout form -->
    <!-- The form sends POST data to CheckoutController::placeOrder -->
    <form action="/?url=checkout/placeOrder" method="post">

        <!-- Customer name input -->
        <div>
            <label>Name:<br>
                <input type="text" name="name" required>
            </label>
        </div>

        <!-- Customer email input -->
        <div>
            <label>Email:<br>
                <input type="email" name="email" required>
            </label>
        </div>

        <!-- Shipping address input -->
        <div>
            <label>Address:<br>
                <textarea name="address" required></textarea>
            </label>
        </div>

        <!-- Submit button -->
        <button type="submit">Pay (Demo)</button>
    </form>
<?php endif; ?>