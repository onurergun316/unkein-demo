<?php
// Controller/HomeController.php

declare(strict_types=1);

require_once __DIR__ . '/../Model/ProductRepository.php';

/**
 * Class HomeController
 *
 * The HomeController is responsible for displaying the home page
 * (the main landing page of the shop). It fetches product data from
 * the `ProductRepository` and passes it to the corresponding view
 * (`View/Home/index.php`) for rendering.
 *
 * This controller does not extend BaseController, since it performs
 * a simple render using `include` rather than the full layout rendering
 * pipeline used by other controllers (like Admin or Checkout).
 */
class HomeController
{
    /**
     * The main entry point of the web application.
     *
     * Fetches all products from the repository and loads the
     * home page view with the data.
     *
     * @return void
     */
    public function index(): void
    {
        // Create a new instance of the product repository
        $repo = new ProductRepository();

        // Retrieve all available products from the database
        $products = $repo->all();

        // Include the view responsible for displaying products
        // This file will receive access to $products
        include __DIR__ . '/../View/Home/index.php';
    }
}