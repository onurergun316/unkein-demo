<?php
// Model/ProductRepository.php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/Product.php';

class ProductRepository
{
    /** @return Product[] */
    public function all(): array
    {
        $pdo  = db();
        $rows = $pdo->query("
            SELECT id, name, price, image
            FROM products
            WHERE is_active = 1
            ORDER BY id ASC
        ")->fetchAll();

        return array_map(fn($r) => $this->mapRowToProduct($r), $rows);
    }

    public function findById(string $id): ?Product
    {
        $pdo  = db();
        $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch();
        return $row ? $this->mapRowToProduct($row) : null;
    }

    public function create(string $name, float $price, string $imageFile): int
    {
        $pdo  = db();
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image, is_active) VALUES (?, ?, ?, 1)");
        $stmt->execute([$name, $price, $imageFile]);
        return (int)$pdo->lastInsertId();
    }

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

    /** Soft delete: mark inactive (keeps FK integrity for order history) */
    public function delete(int $id): void
    {
        $pdo  = db();
        $stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    /** @param array<string,mixed> $row */
    private function mapRowToProduct(array $row): Product
    {
        $slug       = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$row['name']), '-'));
        $priceCents = (int) round(((float)$row['price']) * 100);
        $image      = (string)($row['image'] ?? '');
        if ($image !== '' && strpos($image, '/img/') !== 0 && strpos($image, '/uploads/') !== 0) {
            $image = '/img/' . ltrim($image, '/');
        }

        return new Product(
            (string)$row['id'],
            (string)$row['name'],
            $slug,
            $priceCents,
            $image !== '' ? $image : '/img/placeholder.jpg',
            'default',
            10
        );
    }
}