<?php
// Start Session
session_start();

// Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Router Logic
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'PostController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Namespace resolution
$controllerClass = "App\\Controllers\\$controllerName";

// Check if class exists
if (class_exists($controllerClass)) {
    $controller = new $controllerClass();

    if (method_exists($controller, $actionName)) {
        // Call the action
        $controller->$actionName();
    } else {
        // Fallback or 404
        echo "Action '$actionName' not found in $controllerName.";
    }
} else {
    // If controller not found, maybe redirect to posts index or login
    if ($controllerName === 'PostController' && !isset($_GET['controller'])) {
        // If default controller fail, something is wrong, but likely it's just not created yet.
        // For now, let's output a message.
        echo "Welcome to r_social. please initialize controllers.";
    } else {
        echo "Controller '$controllerName' not found.";
    }
}
