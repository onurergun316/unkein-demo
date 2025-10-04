<?php
// View/Home/index.php
// Variables available: $products (Product[])
?>
<h2>Welcome</h2>
<p>Browse our latest products below:</p>

<section>
    <?php if (!empty($products)): ?>
        <ul>
            <?php foreach ($products as $p): ?>
                <li>
                    <strong><?php echo htmlspecialchars($p->name); ?></strong>
                    — <?php echo $p->priceFormatted(); ?>
                    — <a href="/?url=product/show/<?php echo urlencode($p->id); ?>">View</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No products yet.</p>
    <?php endif; ?>
</section>