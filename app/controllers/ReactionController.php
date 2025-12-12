<?php
namespace App\Controllers;

use App\Models\Reaction;
use App\Models\Vote;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Comment;

class ReactionController extends BaseController
{

    private $reactionModel; // Likes
    private $voteModel;     // Comment Votes
    private $notifModel;
    private $postModel; // For fetching post owner
    private $commentModel; // For fetching comment owner

    public function __construct()
    {
        $this->reactionModel = new Reaction();
        $this->voteModel = new Vote();
        $this->notifModel = new Notification();
        $this->postModel = new Post();
        $this->commentModel = new Comment();
    }

    // Toggle Like on Post
    public function toggleLike()
    {
        $this->requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input) {
            $postId = $input['post_id'];
            $userId = $this->getCurrentUserId();

            $action = $this->reactionModel->toggleLike($userId, $postId);
            $newCount = $this->reactionModel->getLikeCount($postId);

            // Notify if liked
            if ($action === 'added') {
                $post = $this->postModel->findById($postId);
                if ($post && $post['utilisateur_id'] != $userId) {
                    $this->notifModel->create(
                        $post['utilisateur_id'],
                        'like',
                        "Quelqu'un a aimé votre post.",
                        "index.php?controller=post&action=show&id=$postId"
                    );
                }
            }

            $this->jsonResponse(['status' => 'success', 'action' => $action, 'count' => $newCount]);
        }
    }

    // Vote on Comment
    public function vote()
    {
        $this->requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input) {
            $commentId = $input['comment_id'];
            $type = $input['type']; // 'up' or 'down'
            $userId = $this->getCurrentUserId();

            $action = $this->voteModel->castVote($userId, $commentId, $type);
            $newScore = $this->voteModel->getScore($commentId);

            // Notify if upvoted
            if ($type === 'up' && ($action === 'added' || $action === 'updated')) {
                $comment = $this->commentModel->findById($commentId);
                if ($comment && $comment['utilisateur_id'] != $userId) {
                    $this->notifModel->create(
                        $comment['utilisateur_id'],
                        'vote',
                        "Quelqu'un a voté pour votre commentaire.",
                        "index.php?controller=post&action=show&id=" . $comment['post_id']
                    );
                }
            }

            $this->jsonResponse(['status' => 'success', 'action' => $action, 'score' => $newScore]);
        }
    }
}
