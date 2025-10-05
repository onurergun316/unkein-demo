// -----------------------------------------------------------------------------
// File: assets/js/app.js
// Project: Unkein E-Commerce Frontend
// Purpose: Adds lightweight, non-intrusive frontend enhancements using jQuery.
// Context: This script is purely for UI interactivity — it does not alter or
//          depend on backend logic. Designed for progressive enhancement.
// -----------------------------------------------------------------------------

// Wait for DOM readiness before executing scripts
$(function () {
  
  // -----------------------------------------------------------
  // FORM SUBMIT FEEDBACK
  // -----------------------------------------------------------
  // Temporarily disables submit buttons upon form submission to
  // prevent accidental double-clicks or multiple requests.
  $(document).on('submit', 'form', function () {
    const $btn = $(this).find('button[type="submit"]');
    if ($btn.length) {
      $btn.prop('disabled', true);
      setTimeout(() => $btn.prop('disabled', false), 1500); // re-enable after 1.5s
    }
  });

  // -----------------------------------------------------------
  // QUANTITY INPUT STEPPERS
  // -----------------------------------------------------------
  // Allows users to increment or decrement <input type="number">
  // values via + / − buttons. Triggers a change event each time
  // to ensure downstream listeners update accordingly.
  
  // Increment button handler
  $(document).on('click', '[data-step="inc"]', function () {
    const $input = $(this).siblings('input[type="number"]');
    const max = parseInt($input.attr('max') || '999', 10);
    let val = parseInt($input.val() || '1', 10);
    if (val < max) $input.val(val + 1).trigger('change');
  });

  // Decrement button handler
  $(document).on('click', '[data-step="dec"]', function () {
    const $input = $(this).siblings('input[type="number"]');
    const min = parseInt($input.attr('min') || '1', 10);
    let val = parseInt($input.val() || '1', 10);
    if (val > min) $input.val(val - 1).trigger('change');
  });

  // -----------------------------------------------------------
  // ADD-TO-CART TOAST (UI-ONLY)
  // -----------------------------------------------------------
  // Displays a short, fading toast notification when the user
  // clicks any element with a `data-add-to-cart` attribute.
  // This does not interact with the backend cart logic.
  $('[data-add-to-cart]').on('click', function () {
    showToast('Added to cart (demo)');
  });

  // -----------------------------------------------------------
  // TOAST HANDLER
  // -----------------------------------------------------------
  // Dynamically creates a toast container (if none exists),
  // appends a message, fades it in, then auto-removes it
  // after 2 seconds.
  function showToast(message) {
    let $c = $('#toast-container');
    if (!$c.length) {
      $c = $('<div id="toast-container" class="toast-container" />').appendTo('body');
    }
    const $t = $('<div class="toast" />').text(message);
    $c.append($t);
    setTimeout(() => $t.addClass('show'), 10);
    setTimeout(() => $t.removeClass('show').fadeOut(200, () => $t.remove()), 2000);
  }
});