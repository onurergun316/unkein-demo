<?php
// Model/ProductRepository.php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Product.php';

class ProductRepository
{
    /** @return Product[] */
    public function all(): array
    {
        $pdo = Database::pdo();
        $stmt = $pdo->query("SELECT id, name, price, image FROM products ORDER BY id ASC");
        $rows = $stmt->fetchAll();

        $products = [];
        foreach ($rows as $row) {
            $products[] = $this->mapRowToProduct($row);
        }
        return $products;
    }

    public function findById(string $id): ?Product
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        return $this->mapRowToProduct($row);
    }

    /** @param array<string,mixed> $row */
    private function mapRowToProduct(array $row): Product
    {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$row['name']), '-'));
        $priceCents = (int)round(((float)$row['price']) * 100);

        $image = $row['image'] ?? '';
        if ($image !== '' && strpos($image, '/img/') !== 0) {
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