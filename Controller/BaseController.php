<?php
// Controller/BaseController.php

declare(strict_types=1);

/**
 * Class BaseController
 *
 * The BaseController acts as the foundation for all controllers in the MVC structure.
 * It provides shared methods and behaviors that can be inherited by specific controllers
 * (like HomeController, ProductController, AdminController, etc.).
 *
 * Its main responsibility is to render views within the common layout structure.
 * This ensures every page uses the same header and footer without duplicating code.
 */
class BaseController
{
    /**
     * Render a view file inside the standard site layout.
     *
     * @param string $viewPath The relative path of the view (e.g. 'Home/index' or 'Product/show')
     * @param array $data Optional associative array of data to be passed into the view
     *
     * @return void
     */
    protected function render(string $viewPath, array $data = []): void
    {
        // Construct the full filesystem path to the requested view
        $fullPath = BASE_PATH . '/View/' . $viewPath . '.php';

        // If the file doesn’t exist, return a 500 Internal Server Error and message
        if (!file_exists($fullPath)) {
            http_response_code(500);
            echo "View not found: $fullPath";
            return;
        }

        // Make $data keys available as local variables inside the included view
        // Example: ['name' => 'Onur'] becomes available as $name
        extract($data, EXTR_SKIP);

        // Include common layout parts around the specific view content
        require BASE_PATH . '/View/layout/header.php';
        require $fullPath;
        require BASE_PATH . '/View/layout/footer.php';
    }
}