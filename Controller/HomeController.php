<?php
// Controller/HomeController.php

declare(strict_types=1);

class HomeController extends BaseController
{
    public function index(): void
    {
        $repo = new ProductRepository();
        $products = $repo->all();

        $this->render('Home/index', [
            'products' => $products,
        ]);
    }
}