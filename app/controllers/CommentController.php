<?php
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;

class CommentController extends BaseController
{

    private $commentModel;
    private $postModel;
    private $notifModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->postModel = new Post();
        $this->notifModel = new Notification();
    }

    public function create()
    {
        $this->requireAuth();

        // Handle JSON for AJAX
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input) {
            $postId = $input['post_id'];
            $contenu = $input['contenu'];
            $userId = $this->getCurrentUserId();

            if ($this->commentModel->create($contenu, $userId, $postId)) {
                // Notify post owner
                $post = $this->postModel->findById($postId);
                if ($post && $post['utilisateur_id'] != $userId) {
                    $this->notifModel->create(
                        $post['utilisateur_id'],
                        'comment',
                        "Quelqu'un a commenté votre post.",
                        "index.php?controller=post&action=show&id=$postId"
                    );
                }

                $this->jsonResponse(['status' => 'success']);
            } else {
                $this->jsonResponse(['status' => 'error']);
            }
        }
    }

    // Inline Edit for Comments
    public function edit()
    {
        $this->requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input) {
            $id = $input['id'];
            $value = $input['value']; // content

            $comment = $this->commentModel->findById($id);
            if ($comment && $comment['utilisateur_id'] == $this->getCurrentUserId()) {
                $this->commentModel->update($id, $value);
                $this->jsonResponse(['status' => 'success']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Unauthorized']);
            }
        }
    }
}
