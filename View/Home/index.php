<?php
// -----------------------------------------------------------
// View/Home/index.php
// -----------------------------------------------------------
// PURPOSE:
//   This is the homepage of the shop. It displays a simple welcome
//   message and a list of active products with names, prices,
//   and links to view individual product details.
//
// DATA PROVIDED BY CONTROLLER:
//   $products — an array of Product objects, supplied by HomeController::index()
//
// ROLE IN MVC:
//   - The View component that renders product listings to the browser.
//   - Gets data from HomeController, which retrieves it via ProductRepository.
//   - Does not contain any business logic (display only).
// -----------------------------------------------------------

// Include common layout header
include __DIR__ . '/../Layout/header.php';
?>

<main>
    <h1>Welcome</h1>
    <p>Browse our latest products below:</p>

    <!-- Product list -->
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?= htmlspecialchars($product->name) ?> –
                <?= htmlspecialchars($product->priceFormatted()) ?>
                —
                <a href="/?url=product/show/<?= urlencode($product->id) ?>">View</a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php
// Include common layout footer
include __DIR__ . '/../Layout/footer.php';
?>