<?php
// public/index.php
declare(strict_types=1);

/**
 * Front Controller – the single entry point of the web application.
 *
 * Responsibilities:
 *  - Initialize the session and environment
 *  - Define absolute paths
 *  - Autoload classes from Controller and Model folders
 *  - Parse incoming URLs (router)
 *  - Dispatch the appropriate controller and action
 */

session_start();

// Define the absolute path to the project root directory
define('BASE_PATH', dirname(__DIR__));

/**
 * Simple autoloader for classes in Controller/ and Model/ directories.
 * Whenever PHP encounters a new class name, this function looks for a matching
 * file path and includes it automatically (no manual require statements needed).
 */
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/Controller/' . $class . '.php',
        BASE_PATH . '/Model/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return; // Stop once the class file is found
        }
    }
});

/**
 * Basic router:
 * URL format → ?url=controller/action/param
 *
 * Examples:
 *  - ?url=home/index       → HomeController::index()
 *  - ?url=product/show/15  → ProductController::show('15')
 */
$url = $_GET['url'] ?? 'home/index';

// Break the URL into parts: ['home', 'index', '15']
$parts = array_values(array_filter(explode('/', $url)));

// Extract controller, action, and optional parameter
$controllerName = ucfirst($parts[0] ?? 'home') . 'Controller';
$action = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

/**
 * Resolve and load the controller file dynamically.
 * If missing, respond with 404 (Not Found).
 */
$controllerFile = BASE_PATH . '/Controller/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Controller file not found: $controllerName";
    exit;
}

require_once $controllerFile;

/**
 * Ensure the expected controller class actually exists.
 * (In case the file was loaded but doesn't define the correct class.)
 */
if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "Controller class not found: $controllerName";
    exit;
}

// Instantiate the controller (object-oriented dispatch)
$controller = new $controllerName();

/**
 * Verify that the requested action (method) exists within the controller.
 * If not, return a 404 error.
 */
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "Action not found: $controllerName::$action";
    exit;
}

/**
 * Finally, invoke the controller method.
 * If there’s an extra URL segment (param), pass it as an argument.
 */
$param !== null
    ? $controller->$action($param)
    : $controller->$action();