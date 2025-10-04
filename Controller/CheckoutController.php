<?php
// Controller/CheckoutController.php
declare(strict_types=1);

require_once __DIR__ . '/../Model/OrderRepository.php';
require_once __DIR__ . '/../Model/ProductRepository.php';
require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/CartItem.php';

/**
 * Class CheckoutController
 *
 * Handles the checkout process:
 * - Displaying the checkout page
 * - Validating customer information
 * - Creating new orders in the database
 * - Rendering order confirmation
 *
 * It connects the user's cart data (stored in session) with the `OrderRepository`
 * for order persistence in MySQL, acting as the “Controller” between View and Model.
 */
class CheckoutController extends BaseController
{
    /**
     * Show the checkout page with a summary of the user's cart.
     *
     * Redirects the user back to the cart page if their cart is empty.
     * Otherwise, renders the `Checkout/index.php` view with the cart data.
     */
    public function index(): void
    {
        $cart = $this->getCart();

        // If the cart is empty, redirect back to the cart page
        if ($cart->isEmpty()) {
            header('Location: /?url=cart/index');
            exit;
        }

        // Otherwise, show the checkout form and order summary
        $this->render('Checkout/index', ['cart' => $cart]);
    }

    /**
     * Handle checkout form submission and place a new order.
     *
     * Validates form data, creates a new order record via OrderRepository,
     * and then clears the user's cart. Finally, renders a confirmation page.
     */
    public function placeOrder(): void
    {
        $cart = $this->getCart();

        // Prevent order placement if cart is empty
        if ($cart->isEmpty()) {
            header('Location: /?url=cart/index');
            exit;
        }

        // Collect and sanitize user-submitted form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Basic validation — all fields must be filled
        if ($name === '' || $email === '' || $address === '') {
            http_response_code(400);
            echo "Please fill all fields.";
            return;
        }

        // Create the order using the repository
        $repo = new OrderRepository();
        $orderId = $repo->create(
            ['name' => $name, 'email' => $email, 'address' => $address],
            $cart
        );

        // Clear the user's cart from session after successful checkout
        $_SESSION['cart'] = serialize(new Cart());

        // Render confirmation page (Checkout/confirm.php)
        $this->render('Checkout/confirm', [
            // Generate an order number like "UK000001"
            'orderNo' => 'UK' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT),

            // Format total as “xx.xx €”
            'total'   => number_format($cart->totalCents() / 100, 2) . ' €',

            // Optional: could list the order items, skipped here
            'items'   => [],

            // Include user info for confirmation
            'name'    => $name,
            'email'   => $email,
            'address' => $address,
        ]);
    }

    /**
     * Retrieve the current user's Cart object from the session.
     *
     * If the cart does not exist, a new one is initialized and serialized.
     * The unserialize step safely restores the Cart and its related objects.
     *
     * @return Cart
     */
    private function getCart(): Cart
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = serialize(new Cart());
        }

        /** @var Cart $cart */
        $cart = unserialize(
            $_SESSION['cart'],
            ['allowed_classes' => [Cart::class, CartItem::class, Product::class]]
        );

        return $cart;
    }
}