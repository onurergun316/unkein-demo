<?php
// Model/CartItem.php

declare(strict_types=1);

/**
 * Class CartItem
 *
 * Represents a single line item inside a user's shopping cart.
 * Each CartItem connects a Product object with a quantity.
 *
 * Example:
 *   Product: "Red T-Shirt" (price 19.99 €)
 *   Quantity: 2
 *   → subtotalCents() = 19.99 × 2 = 39.98 €
 *
 * CartItem is the **building block** of the Cart model.
 * The Cart model contains multiple CartItems, each representing
 * a product the user added.
 */
class CartItem
{
    /** @var Product $product The actual product this item refers to. */
    public Product $product;

    /** @var int $qty The quantity of that product in the cart. */
    public int $qty;

    /**
     * Constructor — create a CartItem instance.
     *
     * @param Product $product  The Product object being added.
     * @param int $qty          The initial quantity (defaults to at least 1).
     */
    public function __construct(Product $product, int $qty)
    {
        $this->product = $product;

        // Ensure quantity never drops below 1
        // (so if user sends 0 or -5, we default to 1)
        $this->qty = max(1, $qty);
    }

    /**
     * Calculate the subtotal of this cart line in cents.
     *
     * Example:
     *   priceCents = 1999 (19.99 €)
     *   qty = 2
     *   → subtotalCents() = 3998
     *
     * @return int Subtotal (price × qty) in cents.
     */
    public function subtotalCents(): int
    {
        return $this->product->priceCents * $this->qty;
    }
}