<?php
// Model/CartItem.php

declare(strict_types=1);

class CartItem
{
    public Product $product;
    public int $qty;

    public function __construct(Product $product, int $qty)
    {
        $this->product = $product;
        $this->qty = max(1, $qty);
    }

    public function subtotalCents(): int
    {
        return $this->product->priceCents * $this->qty;
    }
}