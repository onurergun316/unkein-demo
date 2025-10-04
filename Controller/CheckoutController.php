<?php
// Controller/CheckoutController.php

declare(strict_types=1);

class CheckoutController extends BaseController
{
    public function index(): void
    {
        $cart = $this->getCart();
        if ($cart->isEmpty()) {
            header('Location: /?url=cart/index');
            exit;
        }

        $this->render('Checkout/index', ['cart' => $cart]);
    }

    public function placeOrder(): void
    {
        // In this phase, we just mock the order placement.
        // Expect POST: name, email, address
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $cart = $this->getCart();
        if ($cart->isEmpty()) {
            header('Location: /?url=cart/index');
            exit;
        }

        if ($name === '' || $email === '' || $address === '') {
            http_response_code(400);
            echo "Please fill all fields.";
            return;
        }

        // Generate a mock order number
        $orderNo = 'UK' . date('YmdHis') . rand(100, 999);

        // In DB phase we will persist it; for now, just clear cart and show confirm
        $total = $cart->total();
        $items = $cart->items();

        // Clear the cart
        $_SESSION['cart'] = serialize(new Cart());

        $this->render('Checkout/confirm', [
            'orderNo' => $orderNo,
            'total'   => $total,
            'items'   => $items,
            'name'    => $name,
            'email'   => $email,
            'address' => $address,
        ]);
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
}