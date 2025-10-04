<?php 
// View/Admin/dashboard.php
// ---------------------------------------------------------
// This is the Admin Dashboard view.
//
// It is rendered through BaseController::render(), which means
// the global layout (header.php and footer.php) is automatically
// included before and after this file.
//
// $orders → provided by AdminController::dashboard()
// ---------------------------------------------------------
?>

<h2>Admin Dashboard</h2>

<p>
  <!-- Navigation buttons for admin actions -->
  <a class="btn" href="/?url=admin/products">Manage Products</a>
  <a class="btn ghost" href="/?url=admin/logout">Logout</a>
</p>

<h3>Recent Orders</h3>

<!-- Table showing the latest customer orders -->
<table class="table">
  <thead>
    <tr>
      <!-- Table headers defining each order attribute -->
      <th>ID</th>
      <th>Customer</th>
      <th>Email</th>
      <th>Total</th>
      <th>When</th>
      <th>Items</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($orders as $o): ?>
      <tr>
        <!-- Display each order as a table row -->
        <td><?= (int)$o['id'] ?></td>
        <td><?= htmlspecialchars($o['customer_name']) ?></td>
        <td><?= htmlspecialchars($o['customer_email']) ?></td>
        <td><?= number_format((float)$o['total'], 2) ?> €</td>
        <td><?= htmlspecialchars($o['created_at']) ?></td>
        <td><?= (int)$o['items'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>