<?php
namespace App\Models;

use PDO;

class Reaction extends BaseModel
{

    public function hasLiked($userId, $postId)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        return $stmt->fetch() !== false;
    }

    public function toggleLike($userId, $postId)
    {
        if ($this->hasLiked($userId, $postId)) {
            $stmt = $this->pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            return 'removed';
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            $stmt->execute([$userId, $postId]);
            return 'added';
        }
    }

    public function getLikeCount($postId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
}
