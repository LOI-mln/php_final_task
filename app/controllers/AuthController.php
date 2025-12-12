<?php
namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $this->redirect('index.php?controller=post&action=index');
            } else {
                $error = "Email ou mot de passe incorrect.";
                $this->render('auth/login', ['error' => $error]);
            }
        } else {
            $this->render('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if ($this->userModel->create($nom, $email, $password)) {
                $this->redirect('index.php?controller=auth&action=login');
            } else {
                $error = "Erreur lors de l'inscription.";
                $this->render('auth/register', ['error' => $error]);
            }
        } else {
            $this->render('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('index.php?controller=auth&action=login');
    }
}
