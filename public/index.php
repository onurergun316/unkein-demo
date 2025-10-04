<?php
// public/index.php

declare(strict_types=1);

session_start();

// Absolute project root
define('BASE_PATH', dirname(__DIR__));

// Simple autoloader for Controller/ and Model/
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/Controller/' . $class . '.php',
        BASE_PATH . '/Model/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Basic router: ?url=controller/action/param
$url = $_GET['url'] ?? 'home/index';
$parts = array_values(array_filter(explode('/', $url)));

$controllerName = ucfirst($parts[0] ?? 'home') . 'Controller';
$action = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

// Resolve and dispatch
$controllerFile = BASE_PATH . '/Controller/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Controller file not found: $controllerName";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "Controller class not found: $controllerName";
    exit;
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "Action not found: $controllerName::$action";
    exit;
}

// Call controller action
$param !== null ? $controller->$action($param) : $controller->$action();