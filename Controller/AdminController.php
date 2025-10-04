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

class AdminController extends BaseController
{
    // --- Auth ---
    public function login(): void
    {
        if ($this->isLoggedIn()) {
            header('Location: /?url=admin/dashboard');
            exit;
        }
        $this->render('Admin/login');
    }

    public function doLogin(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $pdo = db();
        $stmt = $pdo->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = (int)$row['id'];
            $_SESSION['admin_user'] = $row['username'];
            header('Location: /?url=admin/dashboard');
            exit;
        }

        $this->render('Admin/login', ['error' => 'Invalid credentials.']);
    }

    public function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_user']);
        header('Location: /?url=admin/login');
        exit;
    }

    // --- Dashboard ---
    public function dashboard(): void
    {
        $this->requireAuth();
        $orders = (new OrderRepository())->listRecent();
        $this->render('Admin/dashboard', ['orders' => $orders]);
    }

    // --- Product CRUD ---
    public function products(): void
    {
        $this->requireAuth();
        $repo = new ProductRepository();
        $this->render('Admin/products', ['products' => $repo->all()]);
    }

    public function newProduct(): void
    {
        $this->requireAuth();
        $this->render('Admin/product_form', ['mode' => 'create']);
    }

    public function createProduct(): void
    {
        $this->requireAuth();

        $name  = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if ($name === '' || $price <= 0) {
            $this->render('Admin/product_form', ['mode' => 'create', 'error' => 'Name and price required.']);
            return;
        }

        $imageFile = $this->handleUpload(); // returns filename or ''
        $repo = new ProductRepository();
        $repo->create($name, $price, $imageFile !== '' ? $imageFile : 'placeholder.jpg');

        header('Location: /?url=admin/products');
        exit;
    }

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

        $newImage = $this->handleUpload(); // '' if none
        $repo = new ProductRepository();
        $repo->update($id, $name, $price, $newImage !== '' ? $newImage : null);

        header('Location: /?url=admin/products');
        exit;
    }

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

    // --- Helpers ---
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: /?url=admin/login');
            exit;
        }
    }

    /** Returns stored filename under /uploads or '' if none */
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

        if (!in_array($ext, ['jpg','jpeg','png','gif'], true)) {
            return '';
        }

        // Save into public/img (same folder as your existing images)
        $targetDir = dirname(__DIR__) . '/public/img';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        // Use a generated filename to avoid collisions
        $filename = uniqid('p_', true) . '.' . $ext;
        $dest     = $targetDir . '/' . $filename;

        if (!move_uploaded_file($tmp, $dest)) {
            return '';
        }

        // IMPORTANT: return only the filename. The repository will prefix /img/
        return $filename;
    }
}