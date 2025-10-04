<?php
// Model/OrderRepository.php
declare(strict_types=1);

// --- Required dependencies ---
// We include these files because OrderRepository needs to:
// - Talk to the database (`db.php`)
// - Access Product details when saving orders (`Product.php`)
// - Work with Cart and CartItem objects passed from CheckoutController
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/CartItem.php';

/**
 * Class OrderRepository
 *
 * Handles all order-related database operations.
 * This class acts as a "Repository" — a data-access layer between
 * the application's business logic (controllers) and the MySQL database.
 *
 * Responsibilities:
 *  - Create new orders in the database (with line items)
 *  - Retrieve recent order summaries for the admin dashboard
 */
class OrderRepository
{
    /**
     * Creates a new order and its associated order items in the database.
     * 
     * @param array{name:string,email:string,address:string} $customer
     *        The buyer’s name, email, and address.
     * @param Cart $cart
     *        The current user's shopping cart object.
     * 
     * @return int
     *        Returns the newly created order's ID (auto-incremented).
     */
    public function create(array $customer, Cart $cart): int
    {
        // Establish a connection to the MySQL database.
        $pdo = db();

        // Start a transaction to ensure atomicity — either all inserts succeed or none.
        $pdo->beginTransaction();
        try {
            // Calculate total order value (convert from cents to euros)
            $totalDec = $cart->totalCents() / 100.0;

            // 1️⃣ Insert the order header (basic customer info, no line items yet)
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

            // Get the auto-generated order ID for linking line items.
            $orderId = (int)$pdo->lastInsertId();

            // 2️⃣ Insert each item in the cart as a separate row in `order_items`.
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, qty, price_each)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($cart->items() as $line) {
                $itemStmt->execute([
                    $orderId,                        // Link to the order ID
                    (int)$line->product->id,         // Product foreign key
                    (int)$line->qty,                 // Quantity purchased
                    $line->product->priceCents / 100.0 // Price per unit (converted to euros)
                ]);
            }

            // 3️⃣ If all inserts succeed, commit the transaction.
            $pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            // If *any* error occurs (DB issue, constraint violation, etc.),
            // roll back everything to maintain data consistency.
            $pdo->rollBack();
            http_response_code(500);
            echo "Failed to place order: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }

    /**
     * Returns a summary of the most recent orders for the admin dashboard.
     * 
     * @param int $limit
     *        Maximum number of orders to retrieve (default: 25).
     * 
     * @return array
     *        An array of associative arrays, each representing an order summary.
     *        Example:
     *        [
     *          ['id' => 1, 'customer_name' => 'John Doe', 'total' => 54.99, 'items' => 3, ...],
     *          ...
     *        ]
     */
    public function listRecent(int $limit = 25): array
    {
        $pdo  = db();

        // SQL joins orders and order_items to count how many items were in each order.
        $stmt = $pdo->query("
            SELECT 
                o.id, 
                o.customer_name, 
                o.customer_email, 
                o.total, 
                o.created_at,
                COUNT(oi.id) AS items
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.id DESC
            LIMIT {$limit}
        ");

        // Fetch results as associative arrays.
        return $stmt->fetchAll();
    }
}