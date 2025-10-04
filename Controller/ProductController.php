<?php
// Controller/ProductController.php

declare(strict_types=1);

/**
 * Class ProductController
 *
 * The ProductController is responsible for displaying the list of all products
 * and showing detailed information for a single product.
 *
 * It connects the user interface (views) with the product data (via ProductRepository).
 * This class extends BaseController, allowing it to use the shared `render()` method
 * to display views consistently within the app's layout (header + footer).
 */
class ProductController extends BaseController
{
    /**
     * Display a list of all products in the store.
     *
     * This method retrieves all products from the ProductRepository
     * and passes them to the `Product/index` view for rendering.
     *
     * @return void
     */
    public function index(): void
    {
        // Fetch product data from the database or in-memory repository
        $repo = new ProductRepository();
        $products = $repo->all();

        // Render the product listing page and pass the product data
        $this->render('Product/index', [
            'products' => $products,
        ]);
    }

    /**
     * Display a single product’s details page.
     *
     * @param string $id The unique identifier of the product to display.
     *
     * If the product is not found, the method returns a 404 error response
     * and renders the same `Product/show` view with a null product value,
     * so the view can gracefully handle missing products.
     *
     * @return void
     */
    public function show(string $id): void
    {
        // Fetch the product by its ID
        $repo = new ProductRepository();
        $product = $repo->findById($id);

        // If the product doesn’t exist, return a 404 error and show an empty product view
        if (!$product) {
            http_response_code(404);
            $this->render('Product/show', ['product' => null]);
            return;
        }

        // Render the product details view with the found product
        $this->render('Product/show', ['product' => $product]);
    }
}