<?php
// Controller/BaseController.php

declare(strict_types=1);

class BaseController
{
    protected function render(string $viewPath, array $data = []): void
    {
        // $viewPath like 'Home/index' or 'Product/show'
        $fullPath = BASE_PATH . '/View/' . $viewPath . '.php';

        if (!file_exists($fullPath)) {
            http_response_code(500);
            echo "View not found: $fullPath";
            return;
        }

        // Extract $data to variables for the view
        extract($data, EXTR_SKIP);

        require BASE_PATH . '/View/layout/header.php';
        require $fullPath;
        require BASE_PATH . '/View/layout/footer.php';
    }
}