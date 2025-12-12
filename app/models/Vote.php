<?php
namespace App\Models;

use PDO;

class Vote extends BaseModel
{

    public function castVote($userId, $commentId, $type)
    {
        // Build 'ON DUPLICATE KEY UPDATE' logic or check existing
        // Check if vote exists
        $stmt = $this->pdo->prepare("SELECT * FROM votes WHERE user_id = ? AND comment_id = ?");
        $stmt->execute([$userId, $commentId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['vote_type'] === $type) {
                // Same vote? Remove it (toggle off)
                $this->pdo->prepare("DELETE FROM votes WHERE id = ?")->execute([$existing['id']]);
                return 'removed';
            } else {
                // Change vote
                $this->pdo->prepare("UPDATE votes SET vote_type = ? WHERE id = ?")->execute([$type, $existing['id']]);
                return 'updated';
            }
        } else {
            // New vote
            $stmt = $this->pdo->prepare("INSERT INTO votes (user_id, comment_id, vote_type) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $commentId, $type]);
            return 'added';
        }
    }

    public function getScore($commentId)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
            (SELECT COUNT(*) FROM votes WHERE comment_id = ? AND vote_type = 'up') - 
            (SELECT COUNT(*) FROM votes WHERE comment_id = ? AND vote_type = 'down') as score
        ");
        $stmt->execute([$commentId, $commentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['score'];
    }
}
