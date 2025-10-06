<?php
// -----------------------------------------------------------------------------
// File: View/layout/footer.php
// Purpose: Global footer; centered text on all screen sizes.
// Notes:
//   - Uses the same small helper styles declared in header.php
//   - Keeps scripts at the end of the body for better performance.
// -----------------------------------------------------------------------------
?>
  </main>

  <footer class="site-footer">
    <div class="container">
      <small>© <?= date('Y'); ?> Unkein Demo Shop</small>
    </div>
  </footer>

  <!-- JS at the bottom for faster first paint -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Your jQuery UI helpers (toast, filters, menu toggle binder hooks) -->
  <script src="/js/app.js?v=1"></script>
  <!-- Vanilla helpers (quantity steppers, etc.) -->
  <script src="/js/main.js?v=1"></script>
</body>
</html>