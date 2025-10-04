<?php
// Model/Cart.php

declare(strict_types=1);

/**
 * Class Cart
 *
 * Represents a user's shopping cart. This model manages the collection of items
 * (each represented as a `CartItem`) that the user adds during their session.
 *
 * The Cart is **session-based**, meaning it lives only while the user is browsing.
 * It stores all selected products, their quantities, and can calculate totals.
 *
 * This class operates purely in memory — it doesn't directly touch the database.
 * Persistent storage (like orders) happens later through repositories.
 */
class Cart
{
    /** 
     * @var array<string, CartItem> $lines
     * 
     * Holds all cart lines (items). The key is the product’s unique ID,
     * and the value is the corresponding CartItem object containing
     * the product and its quantity.
     */
    private array $lines = [];

    /**
     * Add a product to the cart.
     *
     * If the product already exists, increase its quantity.
     * Otherwise, create a new CartItem for it.
     *
     * @param Product $product  The product being added.
     * @param int $qty          Quantity to add (default: 1).
     * @return void
     */
    public function add(Product $product, int $qty = 1): void
    {
        $id = $product->id;

        // If the product is already in the cart, just increase its quantity
        if (isset($this->lines[$id])) {
            $this->lines[$id]->qty += $qty;
        } else {
            // Otherwise, create a new CartItem entry
            $this->lines[$id] = new CartItem($product, $qty);
        }
    }

    /**
     * Update the quantity of a specific product in the cart.
     *
     * If the quantity is zero or less, the product is removed.
     * If the product doesn’t exist, nothing happens.
     *
     * @param string|null $productId  The product ID.
     * @param int $qty                The new quantity.
     * @return void
     */
    public function update(?string $productId, int $qty): void
    {
        if ($productId === null) return;
        if (!isset($this->lines[$productId])) return;

        // If quantity is 0 or less, remove the product
        if ($qty <= 0) {
            unset($this->lines[$productId]);
        } else {
            // Otherwise, update its quantity
            $this->lines[$productId]->qty = $qty;
        }
    }

    /**
     * Remove a product entirely from the cart.
     *
     * @param string|null $productId
     * @return void
     */
    public function remove(?string $productId): void
    {
        if ($productId === null) return;
        unset($this->lines[$productId]);
    }

    /**
     * Get all cart items as an array.
     *
     * Used by views or checkout processes to list what’s in the cart.
     *
     * @return CartItem[]
     */
    public function items(): array
    {
        // array_values() ensures a clean numeric array
        return array_values($this->lines);
    }

    /**
     * Calculate the total price of the cart (in cents).
     *
     * Internally sums the subtotal (price × qty) of each item.
     * This method uses array_reduce for functional-style aggregation.
     *
     * @return int  Total amount in cents (to avoid float rounding errors).
     */
    public function totalCents(): int
    {
        return array_reduce(
            $this->lines,
            fn($sum, CartItem $i) => $sum + $i->subtotalCents(),
            0
        );
    }

    /**
     * Get the total price as a formatted string in euros.
     *
     * @return string  Example: "123.45 €"
     */
    public function total(): string
    {
        return number_format($this->totalCents() / 100, 2) . ' €';
    }

    /**
     * Check if the cart is empty (has no items).
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->lines);
    }
}