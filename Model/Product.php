<?php
// Model/Product.php

declare(strict_types=1);

class Product
{
    public string $id;
    public string $name;
    public string $slug;
    public int $priceCents;
    public string $image;
    public string $category;
    public int $stock;

    public function __construct(
        string $id,
        string $name,
        string $slug,
        int $priceCents,
        string $image,
        string $category,
        int $stock
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->priceCents = $priceCents;
        $this->image = $image;
        $this->category = $category;
        $this->stock = $stock;
    }

    public function priceFormatted(): string
    {
        return number_format($this->priceCents / 100, 2) . ' €';
    }
}