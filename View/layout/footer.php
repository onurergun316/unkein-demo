<?php
// -----------------------------------------------------------------------------
// File: View/layout/footer.php
// Project: Unkein E-Commerce Platform
// Purpose: Defines the global footer markup shared across all views.
//
// Context:
//   - Included automatically by BaseController::render() after main content.
//   - Closes the <main> element started in layout/header.php.
//
// Behavior:
//   - Displays copyright notice with dynamic year.
//   - Loads site-wide JavaScript (main.js) for lightweight enhancements.
//
// Styling:
//   - Footer styling handled in _cards.scss under `.site-footer` class.
//   - Uses a centered container for consistent width and spacing.
// -----------------------------------------------------------------------------
?>
  </main>

  <footer class="site-footer">
    <div class="container">
      <small>© <?= date('Y'); ?> Unkein Demo Shop</small>
    </div>
  </footer>

  <script src="/js/main.js"></script>
</body>
</html>