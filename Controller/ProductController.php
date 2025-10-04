<?php
// Controller/ProductController.php

class ProductController
{
    public function index(): void
    {
        require_once __DIR__ . '/../View/Product/index.php';
    }
}