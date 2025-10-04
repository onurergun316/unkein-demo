<?php // header/footer are injected by BaseController::render() ?>
<h2>Admin Dashboard</h2>
<p>
  <a class="btn" href="/?url=admin/products">Manage Products</a>
  <a class="btn ghost" href="/?url=admin/logout">Logout</a>
</p>

<h3>Recent Orders</h3>
<table class="table">
  <thead>
    <tr>
      <th>ID</th><th>Customer</th><th>Email</th><th>Total</th><th>When</th><th>Items</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $o): ?>
      <tr>
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