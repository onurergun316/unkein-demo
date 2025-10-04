<?php
// View/Admin/login.php
// -----------------------------------------------------------
// This view renders the Admin Login page.
//
// It’s automatically wrapped with header and footer
// through BaseController::render().
//
// Variables:
//   $error — optional, contains login error messages
//
// Used by: AdminController::login() and doLogin()
// -----------------------------------------------------------
?>

<h2>Admin Login</h2>

<?php if (!empty($error)): ?>
  <!-- If an error message exists (e.g., invalid credentials),
       display it in a styled paragraph -->
  <p class="notice"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- Admin login form -->
<form action="/?url=admin/doLogin" method="post" style="max-width:420px; display:grid; gap:12px;">

  <!-- Username field -->
  <div>
    <label>Username</label>
    <input class="input" type="text" name="username" required>
  </div>

  <!-- Password field -->
  <div>
    <label>Password</label>
    <input class="input" type="password" name="password" required>
  </div>

  <!-- Submit button -->
  <button class="btn" type="submit">Login</button>
</form>