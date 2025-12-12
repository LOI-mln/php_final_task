<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;

class PostController extends BaseController
{

    private $postModel;
    private $commentModel;
    private $reactionModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->reactionModel = new Reaction();
    }

    public function index()
    {
        $this->requireAuth();
        $posts = $this->postModel->getAll();

        // Enrich posts with current user specific data (e.g. hasLiked)
        foreach ($posts as &$post) {
            $post['has_liked'] = $this->reactionModel->hasLiked($this->getCurrentUserId(), $post['id']);
        }

        $this->render('posts/index', ['posts' => $posts]);
    }

    public function create()
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $contenu = $_POST['contenu'];
            $userId = $this->getCurrentUserId();

            // Handle AJAX or Form submit
            if ($this->postModel->create($titre, $contenu, $userId)) {
                // If AJAX, return JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $this->jsonResponse(['status' => 'success']);
                }
                $this->redirect('index.php');
            }
        }
        $this->render('posts/create');
    }

    public function show()
    {
        $this->requireAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            $this->redirect('index.php');

        $post = $this->postModel->findById($id);
        $comments = $this->commentModel->getByPostId($id);

        $post['has_liked'] = $this->reactionModel->hasLiked($this->getCurrentUserId(), $post['id']);

        $this->render('posts/show', ['post' => $post, 'comments' => $comments]);
    }

    public function edit()
    { // AJAX Inline Edit
        $this->requireAuth();

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input) {
            $id = $input['id'];
            $field = $input['field']; // 'titre' or 'contenu'
            $value = $input['value'];

            $post = $this->postModel->findById($id);
            if ($post && $post['utilisateur_id'] == $this->getCurrentUserId()) {
                // We need to update specifically one field, but model has update logic for both.
                // Ideally update method should be flexible or we fetch current values.
                // Let's assume we update full object for now or create specific method.
                // Simplification: Update the known field, keep other original
                $newTitre = ($field === 'titre') ? $value : $post['titre'];
                $newContenu = ($field === 'contenu') ? $value : $post['contenu'];

                $this->postModel->update($id, $newTitre, $newContenu);
                $this->jsonResponse(['status' => 'success']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Unauthorized']);
            }
        }
    }

    public function delete()
    {
        $this->requireAuth();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $post = $this->postModel->findById($id);
            if ($post && $post['utilisateur_id'] == $this->getCurrentUserId()) {
                $this->postModel->delete($id);
            }
        }
        $this->redirect('index.php');
    }
}
