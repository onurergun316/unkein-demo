<?php
// View/layout/header.php
// Global layout header with modern nav

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>unkein</title>
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-row">
      <a class="brand" href="/?url=home/index">unkein</a>

      <nav class="main-nav" aria-label="Primary">
        <ul class="nav-list">
          <li><a class="nav-link" href="/?url=home/index">Home</a></li>
          <li><a class="nav-link" href="/?url=product/index">Products</a></li>
          <li><a class="nav-link" href="/?url=cart/index">Cart</a></li>
          <li><a class="nav-link" href="/?url=checkout/index">Checkout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="page">