<?php
// Model/ProductRepository.php
// In Phase 2 (DB) we'll swap this for MySQL.
// For now: static in-memory product catalog.

declare(strict_types=1);

class ProductRepository
{
    /** @return Product[] */
    public function all(): array
    {
        return array_values($this->seed());
    }

    public function findById(string $id): ?Product
    {
        $all = $this->seed();
        return $all[$id] ?? null;
    }

    /** @return array<string, Product> */
    private function seed(): array
    {
        return [
            'p1' => new Product('p1', 'Unkein Hoodie',  'unkein-hoodie', 4999, '/img/hoodie.jpg', 'hoodies', 10),
            'p2' => new Product('p2', 'Unkein T-Shirt', 'unkein-tee',    2499, '/img/tshirt.jpg', 'shirts',  25),
            'p3' => new Product('p3', 'Unkein Cap',     'unkein-cap',    1799, '/img/cap.jpg',    'caps',    15),
        ];
    }
}