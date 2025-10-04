<?php
// Controller/CartController.php

declare(strict_types=1);

/**
 * Class CartController
 *
 * Handles all cart-related actions for the user.
 * This includes viewing the cart, adding items, updating quantities, and removing products.
 *
 * Acts as the bridge between the user's browser (View) and the application's logic/data (Model).
 * It interacts with the `Cart`, `CartItem`, and `ProductRepository` models
 * and renders the appropriate view using `BaseController::render()`.
 */
class CartController extends BaseController
{
    /**
     * Display the current contents of the user's cart.
     *
     * Retrieves the user's cart object from the session (creates one if needed)
     * and renders the cart view with the cart data.
     */
    public function index(): void
    {
        $cart = $this->getCart();
        $this->render('Cart/index', ['cart' => $cart]);
    }

    /**
     * Add a product to the shopping cart.
     *
     * Expects POST data containing `product_id` and an optional `qty` (quantity).
     * If the product is valid, it’s added to the cart and the cart is saved to session storage.
     */
    public function add(): void
    {
        // Expect POST data
        $productId = $_POST['product_id'] ?? null;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        // Validate input
        if (!$productId || $qty < 1) {
            http_response_code(400);
            echo "Invalid input.";
            return;
        }

        // Fetch product from repository
        $repo = new ProductRepository();
        $product = $repo->findById($productId);
        if (!$product) {
            http_response_code(404);
            echo "Product not found.";
            return;
        }

        // Load current cart, add product, and save
        $cart = $this->getCart();
        $cart->add($product, $qty);
        $this->saveCart($cart);

        // Redirect back to cart page
        header('Location: /?url=cart/index');
        exit;
    }

    /**
     * Update the quantity of a specific item in the cart.
     *
     * Expects POST data containing `product_id` and `qty`.
     * If the item exists, its quantity is updated; if not, no change occurs.
     */
    public function update(): void
    {
        $productId = $_POST['product_id'] ?? null;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        $cart = $this->getCart();
        $cart->update($productId, $qty);
        $this->saveCart($cart);

        // Refresh the cart page to reflect changes
        header('Location: /?url=cart/index');
        exit;
    }

    /**
     * Remove a specific product from the cart.
     *
     * Expects POST data with `product_id`.
     * Removes that product from the user's session-based cart and redirects to cart page.
     */
    public function remove(): void
    {
        $productId = $_POST['product_id'] ?? null;

        $cart = $this->getCart();
        $cart->remove($productId);
        $this->saveCart($cart);

        header('Location: /?url=cart/index');
        exit;
    }

    /**
     * Retrieve the user's current Cart object from session.
     *
     * If the cart doesn't exist in the session, a new one is created and serialized.
     * The cart is deserialized when retrieved to restore it as a Cart object.
     *
     * @return Cart
     */
    private function getCart(): Cart
    {
        // If no cart exists in session, create one
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = serialize(new Cart());
        }

        // Unserialize (decode) the cart back into a Cart object
        /** @var Cart $cart */
        $cart = unserialize(
            $_SESSION['cart'],
            ['allowed_classes' => [Cart::class, CartItem::class, Product::class]]
        );
        return $cart;
    }

    /**
     * Save the user's Cart object into the session.
     *
     * The Cart object is serialized to safely store its structure as a string.
     * This ensures cart data persists between page reloads during the session.
     *
     * @param Cart $cart
     * @return void
     */
    private function saveCart(Cart $cart): void
    {
        $_SESSION['cart'] = serialize($cart);
    }
}