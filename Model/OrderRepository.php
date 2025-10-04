<?php
// Model/OrderRepository.php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/CartItem.php';

class OrderRepository
{
    /**
     * Persists an order + its items. Returns new order ID.
     * @param array{name:string,email:string,address:string} $customer
     */
    public function create(array $customer, Cart $cart): int
    {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $totalDec = $cart->totalCents() / 100.0;

            // Insert order header only (no product_id here)
            $stmt = $pdo->prepare("
                INSERT INTO orders (customer_name, customer_email, address, total)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $customer['name'],
                $customer['email'],
                $customer['address'],
                $totalDec
            ]);
            $orderId = (int)$pdo->lastInsertId();

            // Insert line items
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, qty, price_each)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($cart->items() as $line) {
                $itemStmt->execute([
                    $orderId,
                    (int)$line->product->id,
                    (int)$line->qty,
                    $line->product->priceCents / 100.0
                ]);
            }

            $pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo "Failed to place order: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }

    /** Returns latest orders with item counts */
    public function listRecent(int $limit = 25): array
    {
        $pdo  = db();
        $stmt = $pdo->query("
            SELECT o.id, o.customer_name, o.customer_email, o.total, o.created_at,
                   COUNT(oi.id) AS items
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.id DESC
            LIMIT {$limit}
        ");
        return $stmt->fetchAll();
    }
}