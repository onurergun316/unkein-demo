<?php
// Model/Product.php
declare(strict_types=1);

/**
 * Class Product
 *
 * Represents a single product in the Unkein store.
 * 
 * This is a **data model** — a plain object holding attributes about one product
 * (like name, price, image, and stock quantity).
 * 
 * In the MVC pattern:
 *  - Models store and manage data.
 *  - Controllers manipulate models and send them to Views.
 *  - Views render the model data into HTML.
 * 
 * This class is simple and does *not* directly interact with the database.
 * Database logic is handled separately by ProductRepository.
 */
class Product
{
    // --- Properties ---
    // These correspond directly to columns in the `products` MySQL table.
    public string $id;         // Unique identifier (primary key)
    public string $name;       // Product display name (e.g. "Unkein Hoodie")
    public string $slug;       // URL-friendly name (e.g. "unkein-hoodie")
    public int $priceCents;    // Price stored in cents to avoid float rounding issues
    public string $image;      // Relative path or filename of product image
    public string $category;   // Category label (e.g. "shirts", "hoodies")
    public int $stock;         // Number of units available in inventory

    /**
     * Constructor for Product objects.
     * This initializes the product’s attributes when an instance is created.
     *
     * Example:
     * $hoodie = new Product('1', 'Unkein Hoodie', 'unkein-hoodie', 4999, 'hoodie.jpg', 'hoodies', 10);
     */
    public function __construct(
        string $id,
        string $name,
        string $slug,
        int $priceCents,
        string $image,
        string $category,
        int $stock
    ) {
        // Store passed values into object properties.
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->priceCents = $priceCents;
        $this->image = $image;
        $this->category = $category;
        $this->stock = $stock;
    }

    /**
     * Returns a nicely formatted string version of the price.
     *
     * Converts from integer cents (e.g., 4999) to a readable euro amount like "49.99 €".
     *
     * This is useful for displaying prices in Views without repeating
     * formatting logic throughout the codebase.
     */
    public function priceFormatted(): string
    {
        return number_format($this->priceCents / 100, 2) . ' €';
    }
}