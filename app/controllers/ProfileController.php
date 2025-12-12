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

        // Get user's posts (Need to filter in model or here)
        // I will use getAll and filter here for simplicity as I didn't add getByUserId in Post model.
        // Optimization: Add getByUserId in Post Model.
        // For now, let's filter array or add Method to Post Model if easy.

        // Let's add simple logic here or use getAll
        $allPosts = $this->postModel->getAll();
        $userPosts = array_filter($allPosts, function ($p) use ($id) {
            return $p['utilisateur_id'] == $id;
        });

        $this->render('user/profile', [
            'user' => $user,
            'posts' => $userPosts
        ]);
    }
}
