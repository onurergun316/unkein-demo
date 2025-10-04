<?php
// View/Product/index.php
// Variables: $products (Product[])
?>
<h2>Products</h2>
<ul>
    <?php foreach ($products as $p): ?>
        <li>
            <strong><?php echo htmlspecialchars($p->name); ?></strong>
            — <?php echo $p->priceFormatted(); ?>
            — <a href="/?url=product/show/<?php echo urlencode($p->id); ?>">Details</a>
        </li>
    <?php endforeach; ?>
</ul>