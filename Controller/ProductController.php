<?php
// Controller/ProductController.php

declare(strict_types=1);

class ProductController extends BaseController
{
    public function index(): void
    {
        $repo = new ProductRepository();
        $products = $repo->all();

        $this->render('Product/index', [
            'products' => $products,
        ]);
    }

    public function show(string $id): void
    {
        $repo = new ProductRepository();
        $product = $repo->findById($id);

        if (!$product) {
            http_response_code(404);
            $this->render('Product/show', ['product' => null]);
            return;
        }

        $this->render('Product/show', ['product' => $product]);
    }
}