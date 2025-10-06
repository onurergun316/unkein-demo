// -----------------------------------------------------------------------------
// File: public/js/app.js
// Project: Unkein E-Commerce Platform
// Purpose:
//   Provides lightweight jQuery-based UI enhancements, including:
//     - Preventing double form submissions.
//     - Displaying a centered toast notification when adding a product to cart.
//     - Product price filtering (All / Under 100€ / 100€–200€ / Over 200€).
//
// Context:
//   This script is globally loaded in layout/footer.php and enhances all pages.
//
// Notes:
//   - Uses minimal jQuery for graceful progressive enhancement.
//   - All visual styles (e.g. toast, filters) are defined in SCSS.
// -----------------------------------------------------------------------------

(function () {
  // ---------------------------------------------------------------------------
  // SAFETY CHECK: Abort if jQuery not available
  // ---------------------------------------------------------------------------
  if (typeof window.jQuery === 'undefined') return;

  // ---------------------------------------------------------------------------
  // MAIN EXECUTION: Run when DOM is ready
  // ---------------------------------------------------------------------------
  $(function () {

    // -------------------------------------------------------------------------
    // 1. GLOBAL FORM PROTECTION
    // -------------------------------------------------------------------------
    // Prevent accidental double submissions on any form across the site.
    // Temporarily disables the submit button for 1.5 seconds after click.
    // -------------------------------------------------------------------------
    $(document).on('submit', 'form', function () {
      const $btn = $(this).find('button[type="submit"]');
      if ($btn.length) {
        $btn.prop('disabled', true);
        setTimeout(() => $btn.prop('disabled', false), 1500);
      }
    });

    // -------------------------------------------------------------------------
    // 2. ADD-TO-CART INTERACTION
    // -------------------------------------------------------------------------
    // Displays a centered toast notification ("Added to cart") when the user
    // clicks the Add to Cart button. The form is then submitted after a short
    // delay to ensure the toast is visible before redirection.
    // -------------------------------------------------------------------------
    $(document).on('click', '[data-add-to-cart]', function (e) {
      const $form = $(this).closest('form');
      const action = ($form.attr('action') || '').toLowerCase();

      // Only handle actual "cart/add" forms
      if (!action.includes('cart/add')) return;

      e.preventDefault(); // stop immediate submission
      showToast('Added to cart'); // display toast

      // Submit form after short delay (~0.7s)
      setTimeout(() => $form.trigger('submit'), 700);
    });

    // -------------------------------------------------------------------------
    // TOAST CREATOR
    // -------------------------------------------------------------------------
    // Builds and displays a centered notification overlay.
    // The toast fades in/out using CSS transitions (no jQuery fade).
    // -------------------------------------------------------------------------
    function showToast(message) {
      let $overlay = $('#toast-overlay');

      // Create overlay if it doesn’t exist yet
      if (!$overlay.length) {
        $overlay = $('<div id="toast-overlay" aria-live="polite" aria-atomic="true" />')
          .appendTo('body');
      }

      // Append toast content
      const $card = $('<div class="toast-card" />').text(message);
      $overlay.empty().append($card);

      // Trigger CSS-based show animation
      requestAnimationFrame(() => $overlay.addClass('show'));

      // Remove overlay and content after 1.7 seconds
      setTimeout(() => $overlay.removeClass('show').empty(), 1700);
    }

    // -------------------------------------------------------------------------
    // 3. PRODUCT PRICE FILTER
    // -------------------------------------------------------------------------
    // Filters visible product cards based on selected price range.
    // Buttons use the `data-price-filter` attribute with one of:
    //   - all, lt100, 100to200, gt200
    //
    // Logic:
    //   - "all" shows everything
    //   - "lt100" shows products < 100€
    //   - "100to200" shows products between 100–200€
    //   - "gt200" shows products > 200€
    // -------------------------------------------------------------------------
    $(document).on('click', '[data-price-filter]', function () {
      const filter = $(this).data('price-filter');
      const $cards = $('.card-grid .card');

      // Update active filter state
      $(this).addClass('active').siblings().removeClass('active');

      // Iterate through all product cards
      $cards.each(function () {
        const priceText = $(this).find('.price').text().replace(/[^\d.]/g, '');
        const price = parseFloat(priceText) || 0;

        const visible =
          filter === 'all' ||
          (filter === 'lt100' && price < 100) ||
          (filter === '100to200' && price >= 100 && price <= 200) ||
          (filter === 'gt200' && price > 200);

        $(this).toggle(visible);
      });
    });

  }); // end DOM ready
})(); // end IIFE