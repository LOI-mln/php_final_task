<?php
namespace App\Controllers;

class BaseController
{

    protected function render($view, $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Start output buffering
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Include the main layout wrapper
            require_once __DIR__ . '/../Views/layouts/main.php';
        } else {
            echo "View '$view' not found.";
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    protected function getCurrentUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('index.php?controller=auth&action=login');
        }
    }
}
