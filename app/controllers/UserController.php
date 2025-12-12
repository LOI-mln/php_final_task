<?php
namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        // Default action for User controller
        // Could redirect to profile or list users
        $this->redirect('index.php?controller=profile&action=show');
    }

    public function profile()
    {
        // Redirect legacy profile action to ProfileController
        $this->redirect('index.php?controller=profile&action=show');
    }
}
