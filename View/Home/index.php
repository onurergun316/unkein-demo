<?php include __DIR__ . '/../Layout/header.php'; ?>

<main>
    <h1>Welcome</h1>
    <p>Browse our latest products below:</p>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?= htmlspecialchars($product->name) ?> –
                <?= htmlspecialchars($product->priceFormatted()) ?>
                — <a href="/?url=product/show/<?= $product->id ?>">View</a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include __DIR__ . '/../Layout/footer.php'; ?>