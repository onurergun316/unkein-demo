<?php
// Controller/AdminController.php 
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/ProductRepository.php';
require_once __DIR__ . '/../Model/OrderRepository.php';
require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/CartItem.php';
require_once __DIR__ . '/../Model/Product.php';

/**
 * Class AdminController
 *
 * This controller handles all admin-related functionality such as:
 *  - Logging in and out of the admin panel
 *  - Managing products (CRUD operations)
 *  - Displaying the dashboard with recent orders
 *
 * It extends BaseController, giving it access to the shared `render()` method
 * for consistent header/footer layout handling.
 */
class AdminController extends BaseController
{
    // -------------------- AUTH SECTION --------------------

    /**
     * Display the admin login page.
     * If the user is already authenticated, redirect to the dashboard.
     *
     * @return void
     */
    public function login(): void
    {
        if ($this->isLoggedIn()) {
            header('Location: /?url=admin/dashboard');
            exit;
        }
        $this->render('Admin/login');
    }

    /**
     * Handle login form submission and verify credentials.
     *
     * @return void
     */
    public function doLogin(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        // Get database connection via singleton
        $pdo = db();
        $stmt = $pdo->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        // Verify password hash stored in DB
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = (int)$row['id'];
            $_SESSION['admin_user'] = $row['username'];

            // Redirect to dashboard on success
            header('Location: /?url=admin/dashboard');
            exit;
        }

        // Invalid credentials — re-render login with error message
        $this->render('Admin/login', ['error' => 'Invalid credentials.']);
    }

    /**
     * Log the admin out by clearing the session and redirecting.
     *
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_user']);
        header('Location: /?url=admin/login');
        exit;
    }

    // -------------------- DASHBOARD --------------------

    /**
     * Render the admin dashboard showing the latest orders.
     *
     * @return void
     */
    public function dashboard(): void
    {
        $this->requireAuth();
        $orders = (new OrderRepository())->listRecent();
        $this->render('Admin/dashboard', ['orders' => $orders]);
    }

    // -------------------- PRODUCT CRUD --------------------

    /**
     * Display all products for admin management.
     *
     * @return void
     */
    public function products(): void
    {
        $this->requireAuth();
        $repo = new ProductRepository();
        $this->render('Admin/products', ['products' => $repo->all()]);
    }

    /**
     * Show form for creating a new product.
     *
     * @return void
     */
    public function newProduct(): void
    {
        $this->requireAuth();
        $this->render('Admin/product_form', ['mode' => 'create']);
    }

    /**
     * Create a new product and save it to the database.
     *
     * @return void
     */
    public function createProduct(): void
    {
        $this->requireAuth();

        $name  = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if ($name === '' || $price <= 0) {
            $this->render('Admin/product_form', ['mode' => 'create', 'error' => 'Name and price required.']);
            return;
        }

        // Upload product image if available
        $imageFile = $this->handleUpload();

        $repo = new ProductRepository();
        $repo->create($name, $price, $imageFile !== '' ? $imageFile : 'placeholder.jpg');

        header('Location: /?url=admin/products');
        exit;
    }

    /**
     * Show edit form for an existing product.
     *
     * @param int|string $id Product ID to edit
     * @return void
     */
    public function editProduct($id): void
    {
        $this->requireAuth();
        $repo = new ProductRepository();
        $product = $repo->findById((string)$id);

        if (!$product) {
            http_response_code(404);
            echo "Product not found.";
            return;
        }

        $this->render('Admin/product_form', ['mode' => 'edit', 'product' => $product]);
    }

    /**
     * Update an existing product with new data.
     *
     * @return void
     */
    public function updateProduct(): void
    {
        $this->requireAuth();

        $id    = (int)($_POST['id'] ?? 0);
        $name  = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if ($id <= 0 || $name === '' || $price <= 0) {
            http_response_code(400);
            echo "Invalid input.";
            return;
        }

        // Upload new image (optional)
        $newImage = $this->handleUpload();

        $repo = new ProductRepository();
        $repo->update($id, $name, $price, $newImage !== '' ? $newImage : null);

        header('Location: /?url=admin/products');
        exit;
    }

    /**
     * Delete a product from the catalog.
     *
     * @return void
     */
    public function deleteProduct(): void
    {
        $this->requireAuth();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo "Invalid product id.";
            return;
        }

        (new ProductRepository())->delete($id);

        header('Location: /?url=admin/products');
        exit;
    }

    // -------------------- HELPER METHODS --------------------

    /**
     * Check if an admin user is currently logged in.
     *
     * @return bool True if session contains admin_id
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    /**
     * Require authentication before allowing access to admin pages.
     *
     * Redirects to login if user is not authenticated.
     *
     * @return void
     */
    private function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: /?url=admin/login');
            exit;
        }
    }

    /**
     * Handle image upload for products.
     *
     * Saves uploaded image into /public/img with a unique filename
     * and returns the stored filename. Returns '' on failure or
     * if no image was provided.
     *
     * @return string Uploaded filename or empty string
     */
    private function handleUpload(): string
    {
        // No file selected
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        // Any other upload error
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmp  = $_FILES['image']['tmp_name'];
        $orig = basename($_FILES['image']['name']);
        $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
            return '';
        }

        // Save into public/img (same folder as other product images)
        $targetDir = dirname(__DIR__) . '/public/img';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        // Use unique filename to prevent overwriting
        $filename = uniqid('p_', true) . '.' . $ext;
        $dest     = $targetDir . '/' . $filename;

        if (!move_uploaded_file($tmp, $dest)) {
            return '';
        }

        // Return only the filename; the repository adds '/img/' prefix
        return $filename;
    }
}