<?php // header/footer are injected by BaseController::render() ?>

<h2>Products</h2>
<p>
  <a class="btn" href="/?url=admin/newProduct">+ New Product</a>
  <a class="btn ghost" href="/?url=admin/dashboard">Back to Dashboard</a>
</p>

<table class="table">
  <thead>
    <tr>
      <th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p->id) ?></td>
        <td><?= htmlspecialchars($p->name) ?></td>
        <td><?= $p->priceFormatted() ?></td>
        <td>
          <?php if ($p->image): ?>
            <img src="<?= htmlspecialchars($p->image) ?>" alt="" style="height:40px;">
          <?php endif; ?>
        </td>
        <td style="display:flex; gap:8px;">
          <a class="btn secondary" href="/?url=admin/editProduct/<?= urlencode($p->id) ?>">Edit</a>
          <form action="/?url=admin/deleteProduct" method="post" onsubmit="return confirm('Delete product?');">
            <input type="hidden" name="id" value="<?= htmlspecialchars($p->id) ?>">
            <button class="btn ghost" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>