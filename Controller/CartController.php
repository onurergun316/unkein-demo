<?php
// Controller/CartController.php

declare(strict_types=1);

class CartController extends BaseController
{
    public function index(): void
    {
        $cart = $this->getCart();
        $this->render('Cart/index', ['cart' => $cart]);
    }

    public function add(): void
    {
        // Expect POST: product_id, qty
        $productId = $_POST['product_id'] ?? null;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        if (!$productId || $qty < 1) {
            http_response_code(400);
            echo "Invalid input.";
            return;
        }

        $repo = new ProductRepository();
        $product = $repo->findById($productId);
        if (!$product) {
            http_response_code(404);
            echo "Product not found.";
            return;
        }

        $cart = $this->getCart();
        $cart->add($product, $qty);
        $this->saveCart($cart);

        // Redirect back to cart page
        header('Location: /?url=cart/index');
        exit;
    }

    public function update(): void
    {
        // Expect POST: product_id, qty
        $productId = $_POST['product_id'] ?? null;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        $cart = $this->getCart();
        $cart->update($productId, $qty);
        $this->saveCart($cart);

        header('Location: /?url=cart/index');
        exit;
    }

    public function remove(): void
    {
        $productId = $_POST['product_id'] ?? null;

        $cart = $this->getCart();
        $cart->remove($productId);
        $this->saveCart($cart);

        header('Location: /?url=cart/index');
        exit;
    }

    private function getCart(): Cart
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = serialize(new Cart());
        }
        /** @var Cart $cart */
        $cart = unserialize($_SESSION['cart'], ['allowed_classes' => [Cart::class, CartItem::class, Product::class]]);
        return $cart;
    }

    private function saveCart(Cart $cart): void
    {
        $_SESSION['cart'] = serialize($cart);
    }
}