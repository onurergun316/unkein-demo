<?php
// View/Admin/login.php
// Assumes BaseController::render() already included header/footer
// $error may be set

?>
<h2>Admin Login</h2>
<?php if (!empty($error)): ?>
  <p class="notice"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="/?url=admin/doLogin" method="post" style="max-width:420px; display:grid; gap:12px;">
  <div>
    <label>Username</label>
    <input class="input" type="text" name="username" required>
  </div>
  <div>
    <label>Password</label>
    <input class="input" type="password" name="password" required>
  </div>
  <button class="btn" type="submit">Login</button>
</form>