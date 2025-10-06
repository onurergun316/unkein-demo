<?php
// -----------------------------------------------------------------------------
// File: View/layout/header.php
// Project: Unkein E-Commerce Platform
// Purpose: Defines a responsive site header and navigation system that adapts
//          gracefully across desktop and mobile devices.
//
// Context:
//   - This file is included at the top of every page via BaseController::render().
//   - It defines global structure (<html>, <head>, <body>, and <header>).
//
// Behavior:
//   - On desktop: brand logo ("unkein") appears on the left, navigation links on the right.
//   - On mobile (≤768px): brand remains on the left, hamburger menu appears on the right.
//     When tapped, the hamburger expands a dropdown navigation menu.
//   - The design ensures balanced padding on both sides for clean mobile aesthetics.
//
// Styling:
//   - Base styles define structure, spacing, and alignment.
//   - Responsive rules within @media queries handle layout changes.
//   - Footer helper styles are also included for global alignment consistency.
//
// JavaScript:
//   - A small self-invoking script binds jQuery logic to toggle the mobile menu.
//   - The script sets proper ARIA attributes for accessibility compliance.
// -----------------------------------------------------------------------------
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>unkein</title>
  <link rel="stylesheet" href="/css/main.css">

  <!-- ==============================================================
       Inline Responsive Styles
       ==============================================================
       These styles ensure proper layout and usability across devices.
       The header is sticky, mobile-friendly, and visually balanced.
  -->
  <style>
    /* ===== Base header layout ===== */
    .site-header { position: sticky; top: 0; z-index: 100; }
    .header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;   /* Desktop default: brand left, nav right */
      gap: 12px;
      padding: 10px 0;                  /* Vertical spacing; horizontal set per breakpoint */
    }

    /* Brand identity text */
    .brand { 
      font-size: clamp(1.1rem, 3.5vw, 1.35rem); 
      text-transform: lowercase; 
    }

    /* ===== Desktop navigation ===== */
    .main-nav .nav-list {
      display: flex;
      gap: 18px;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-link { 
      display: inline-block; 
      padding: 8px 10px; 
    }

    /* ===== Hamburger (hidden on desktop) ===== */
    .nav-toggle {
      display: none;                     /* hidden by default */
      border: 1px solid rgba(255,255,255,0.18);
      background: transparent;
      color: inherit;
      padding: 8px 10px;
      border-radius: 10px;
      line-height: 1;
      cursor: pointer;
    }

    /* Focus state for accessibility */
    .nav-toggle:focus { 
      outline: 2px solid rgba(229,57,53,0.55); 
      outline-offset: 2px; 
    }

    /* ===== Mobile adjustments (≤768px) ===== */
    @media (max-width: 768px) {
      /* Equal padding on both sides for balanced layout */
      .site-header .container { 
        padding-left: 16px; 
        padding-right: 16px; 
      }

      /* Maintain brand left, hamburger right */
      .header-row { 
        justify-content: space-between; 
      }

      /* Display hamburger on mobile */
      .nav-toggle { 
        display: inline-flex; 
        align-items: center; 
        gap: 8px;
      }

      /* Collapsible menu styling */
      .main-nav {
        position: absolute;
        left: 0; right: 0;
        top: 100%;
        background: #1d1f26;
        border-top: 1px solid rgba(255,255,255,0.08);
        display: none;                   /* hidden by default */
      }

      /* When toggled open */
      .main-nav.is-open { 
        display: block; 
      }

      /* Stack links vertically */
      .main-nav .nav-list { 
        flex-direction: column; 
        gap: 0; 
      }

      /* Add separators between items */
      .main-nav .nav-list li + li { 
        border-top: 1px solid rgba(255,255,255,0.06); 
      }

      /* Larger tap area for touch devices */
      .main-nav .nav-link { 
        display: block; 
        padding: 14px 16px; 
      }
    }

    /* ===== Footer centering helper ===== */
    .site-footer .container {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- ==============================================================
       Global Header
       ==============================================================
       Displays brand and navigation links.
       Mobile version includes a toggleable hamburger menu.
  -->
  <header class="site-header">
    <div class="container header-row">
      <!-- Brand / Logo -->
      <a class="brand" href="/?url=home/index">unkein</a>

      <!-- Hamburger button (visible only on mobile) -->
      <button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false">
        <span aria-hidden="true">☰</span>
        <span class="sr-only" style="position:absolute;left:-9999px;">Toggle navigation</span>
      </button>

      <!-- Main navigation menu -->
      <nav id="primary-nav" class="main-nav" aria-label="Primary">
        <ul class="nav-list">
          <li><a class="nav-link" href="/?url=home/index">Home</a></li>
          <li><a class="nav-link" href="/?url=product/index">Products</a></li>
          <li><a class="nav-link" href="/?url=cart/index">Cart</a></li>
          <li><a class="nav-link" href="/?url=checkout/index">Checkout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- ==============================================================
       Main Content Area
       ==============================================================
       Begins after the header and ends in layout/footer.php.
  -->
  <main class="page">

  <!-- ==============================================================
       Mobile Navigation Toggle Script
       ==============================================================
       Handles open/close behavior of the hamburger menu.
       Automatically initializes when jQuery is loaded.
  -->
  <script>
    (function () {
      function bindToggle($) {
        var $btn = $('.nav-toggle');
        var $nav = $('#primary-nav');
        if (!$btn.length || !$nav.length) return;

        // Toggle the menu open/close on click
        $btn.on('click', function () {
          var open = $nav.toggleClass('is-open').hasClass('is-open');
          $btn.attr('aria-expanded', open ? 'true' : 'false');
        });
      }

      // If jQuery already loaded
      if (window.jQuery) { jQuery(bindToggle); }

      // Otherwise, wait for DOMContentLoaded to bind once jQuery is available
      document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery) jQuery(bindToggle);
      });
    })();
  </script>