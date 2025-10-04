// assets/js/app.js
// Lightweight progressive enhancements with jQuery (no backend changes)

// Wait for DOM
$(function () {
  // Visual feedback on any form submit (disable button briefly)
  $(document).on('submit', 'form', function () {
    const $btn = $(this).find('button[type="submit"]');
    if ($btn.length) {
      $btn.prop('disabled', true);
      setTimeout(() => $btn.prop('disabled', false), 1500);
    }
  });

  // Quantity steppers for number inputs
  $(document).on('click', '[data-step="inc"]', function () {
    const $input = $(this).siblings('input[type="number"]');
    const max = parseInt($input.attr('max') || '999', 10);
    let val = parseInt($input.val() || '1', 10);
    if (val < max) $input.val(val + 1).trigger('change');
  });

  $(document).on('click', '[data-step="dec"]', function () {
    const $input = $(this).siblings('input[type="number"]');
    const min = parseInt($input.attr('min') || '1', 10);
    let val = parseInt($input.val() || '1', 10);
    if (val > min) $input.val(val - 1).trigger('change');
  });

  // Simple add-to-cart confirmation toast (no AJAX, just UI)
  $('[data-add-to-cart]').on('click', function () {
    showToast('Added to cart (demo)');
  });

  // Toast container
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