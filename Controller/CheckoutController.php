<?php
// Controller/CheckoutController.php
declare(strict_types=1);

require_once __DIR__ . '/../Model/OrderRepository.php';
require_once __DIR__ . '/../Model/ProductRepository.php';
require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/CartItem.php';

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
        $cart = $this->getCart();
        if ($cart->isEmpty()) {
            header('Location: /?url=cart/index');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name === '' || $email === '' || $address === '') {
            http_response_code(400);
            echo "Please fill all fields.";
            return;
        }

        $repo = new OrderRepository();
        $orderId = $repo->create(
            ['name' => $name, 'email' => $email, 'address' => $address],
            $cart
        );

        // Clear the cart
        $_SESSION['cart'] = serialize(new Cart());

        $this->render('Checkout/confirm', [
            'orderNo' => 'UK' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT),
            'total'   => number_format($cart->totalCents() / 100, 2) . ' €',
            'items'   => [], // we just show static lines in confirm, optional
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