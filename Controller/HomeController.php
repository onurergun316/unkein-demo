<?php
// Controller/HomeController.php

declare(strict_types=1);

require_once __DIR__ . '/../Model/ProductRepository.php';

class HomeController
{
    public function index(): void
    {
        $repo = new ProductRepository();
        $products = $repo->all();
        include __DIR__ . '/../View/Home/index.php';
    }
}