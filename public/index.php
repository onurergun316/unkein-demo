<?php
// public/index.php

// Simple autoload (will grow later)
spl_autoload_register(function ($class) {
    $paths = ['../Controller/', '../Model/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Basic routing (for now just ?url=controller/action)
$url = isset($_GET['url']) ? explode('/', $_GET['url']) : ['Home', 'index'];

$controllerName = ucfirst($url[0]) . 'Controller';
$actionName = $url[1] ?? 'index';

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        echo "Action '$actionName' not found.";
    }
} else {
    echo "Controller '$controllerName' not found.";
}