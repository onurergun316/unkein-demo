<?php
// Controller/HomeController.php

class HomeController
{
    public function index(): void
    {
        require_once __DIR__ . '/../View/Home/index.php';
    }
}