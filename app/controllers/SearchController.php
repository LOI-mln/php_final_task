<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class SearchController extends BaseController
{

    private $userModel;
    private $postModel;
    private $commentModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->commentModel = new Comment();
    }

    public function index()
    {
        // Just return JSON search results
        $term = $_GET['q'] ?? '';

        if (strlen($term) < 2) {
            $this->jsonResponse(['users' => [], 'posts' => [], 'comments' => []]);
        }

        $users = $this->userModel->search($term);
        $posts = $this->postModel->search($term);
        $comments = $this->commentModel->search($term);

        $this->jsonResponse([
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments
        ]);
    }
}
