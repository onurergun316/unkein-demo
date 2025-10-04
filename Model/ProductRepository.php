<?php
// Model/ProductRepository.php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/Product.php';

/**
 * Class ProductRepository
 *
 * Data-access layer (Repository pattern) for `products`.
 * Encapsulates all SQL used to read/write product records, keeping controllers clean.
 *
 * Responsibilities:
 *  - Fetch active products
 *  - Find product by ID
 *  - Create/update products
 *  - Soft-delete products (preserve order history and FK integrity)
 */
class ProductRepository
{
    /**
     * Return all active products ordered by ID.
     *
     * @return Product[] Array of Product entities
     */
    public function all(): array
    {
        $pdo  = db();
        $rows = $pdo->query("
            SELECT id, name, price, image
            FROM products
            WHERE is_active = 1
            ORDER BY id ASC
        ")->fetchAll();

        // Map each DB row to a Product entity
        return array_map(fn($r) => $this->mapRowToProduct($r), $rows);
    }

    /**
     * Find a single product by its ID.
     *
     * @param string $id Product ID (cast to int for SQL)
     * @return Product|null Product entity or null if not found
     */
    public function findById(string $id): ?Product
    {
        $pdo  = db();
        $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch();

        return $row ? $this->mapRowToProduct($row) : null;
    }

    /**
     * Create a new product.
     *
     * @param string $name
     * @param float  $price        Price in decimal euros (e.g., 19.99)
     * @param string $imageFile    Stored image filename or path
     * @return int                 Newly inserted product ID
     */
    public function create(string $name, float $price, string $imageFile): int
    {
        $pdo  = db();
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image, is_active) VALUES (?, ?, ?, 1)");
        $stmt->execute([$name, $price, $imageFile]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Update an existing product.
     *
     * If $imageFile is null or empty string, the image is left unchanged.
     *
     * @param int         $id
     * @param string      $name
     * @param float       $price
     * @param string|null $imageFile  New filename or null to keep current image
     * @return void
     */
    public function update(int $id, string $name, float $price, ?string $imageFile = null): void
    {
        $pdo = db();
        if ($imageFile !== null && $imageFile !== '') {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $price, $imageFile, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            $stmt->execute([$name, $price, $id]);
        }
    }

    /**
     * Soft delete a product by marking it inactive.
     * This keeps order history intact and avoids FK violations.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $pdo  = db();
        $stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * Convert a database row into a Product entity.
     *
     * - Builds a URL-friendly slug from the name.
     * - Converts decimal price to integer cents for internal usage.
     * - Normalizes image path to start with '/img/' unless already absolute (/img/ or /uploads/).
     * - Provides sensible defaults for category and stock (demo purposes).
     *
     * @param array<string,mixed> $row
     * @return Product
     */
    private function mapRowToProduct(array $row): Product
    {
        // Slug: lowercase, replace non-alphanumerics with hyphens, trim ends
        $slug       = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$row['name']), '-'));

        // Convert stored decimal price (e.g. 19.99) to integer cents (1999)
        $priceCents = (int) round(((float)$row['price']) * 100);

        // Normalize image path
        $image      = (string)($row['image'] ?? '');
        if ($image !== '' && strpos($image, '/img/') !== 0 && strpos($image, '/uploads/') !== 0) {
            $image = '/img/' . ltrim($image, '/');
        }

        // Construct the domain entity
        return new Product(
            (string)$row['id'],
            (string)$row['name'],
            $slug,
            $image !== '' ? $priceCents : $priceCents, // (kept as-is; priceCents already computed)
            $image !== '' ? $image : '/img/placeholder.jpg',
            'default', // demo category
            10         // demo stock
        );
    }
}