<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Post;

class ProfileController extends BaseController
{

    private $userModel;
    private $postModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
    }

    public function index()
    {
        $this->show(); // Alias
    }

    public function show()
    {
        $id = $_GET['id'] ?? $this->getCurrentUserId();

        if (!$id) {
            $this->redirect('index.php?controller=auth&action=login');
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->render('errors/404'); // Assuming we had a 404, or just redirect
            return;
        }

        // Get user's posts
        $posts = $this->postModel->getByUserId($id);

        $this->render('user/profile', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function edit()
    {
        $this->requireAuth();
        $id = $this->getCurrentUserId();
        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->redirect('index.php?controller=auth&action=login');
        }

        $this->render('user/edit', ['user' => $user]);
    }

    public function update()
    {
        $this->requireAuth();
        $id = $this->getCurrentUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Basic validation
            if (empty($nom) || empty($email)) {
                // Handle error (flash message would be good here)
                echo "Nom et Email sont requis.";
                return;
            }

            $hashedPassword = null;
            if (!empty($password)) {
                if ($password !== $confirm_password) {
                    echo "Les mots de passe ne correspondent pas.";
                    return;
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $nom, $email, $hashedPassword)) {
                // Update session name if changed
                $_SESSION['user_name'] = $nom; // Assuming we store name in session
                $this->redirect('index.php?controller=profile&action=show');
            } else {
                echo "Erreur lors de la mise à jour.";
            }
        }
    }
}
