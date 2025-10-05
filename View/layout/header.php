<?php
// -----------------------------------------------------------------------------
// File: View/layout/header.php
// Project: Unkein E-Commerce Platform
// Purpose: Defines the global HTML <head> and header layout shared by all views.
//
// Context:
//   - Loaded at the start of every page before the <main> section.
//   - Provides consistent navigation, branding, and metadata across views.
//
// Behavior:
//   - Outputs document structure (<html>, <head>, <body>).
//   - Renders a responsive header with site logo and main navigation links.
//   - Navigation links route through MVC via URL parameters (e.g. ?url=cart/index).
//
// Styling:
//   - Visual styles defined in /css/main.css (compiled from SCSS).
//   - `.site-header` and `.header-row` manage alignment, spacing, and layout.
//   - `.nav-list` and `.nav-link` styled for hover and active states.
// -----------------------------------------------------------------------------
?>
<!doctype html>
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