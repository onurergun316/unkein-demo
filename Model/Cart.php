<?php
// Model/Cart.php

declare(strict_types=1);

class Cart
{
    /** @var array<string, CartItem> */
    private array $lines = [];

    public function add(Product $product, int $qty = 1): void
    {
        $id = $product->id;
        if (isset($this->lines[$id])) {
            $this->lines[$id]->qty += $qty;
        } else {
            $this->lines[$id] = new CartItem($product, $qty);
        }
    }

    public function update(?string $productId, int $qty): void
    {
        if ($productId === null) return;
        if (!isset($this->lines[$productId])) return;

        if ($qty <= 0) {
            unset($this->lines[$productId]);
        } else {
            $this->lines[$productId]->qty = $qty;
        }
    }

    public function remove(?string $productId): void
    {
        if ($productId === null) return;
        unset($this->lines[$productId]);
    }

    /** @return CartItem[] */
    public function items(): array
    {
        return array_values($this->lines);
    }

    public function totalCents(): int
    {
        return array_reduce($this->lines, fn($sum, CartItem $i) => $sum + $i->subtotalCents(), 0);
    }

    public function total(): string
    {
        return number_format($this->totalCents() / 100, 2) . ' €';
    }

    public function isEmpty(): bool
    {
        return empty($this->lines);
    }
}