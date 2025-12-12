<?php
namespace App\Models;

use PDO;

class Comment extends BaseModel
{

    public function getByPostId($postId)
    {
        // Fetch comments with author name and vote score
        // Score = (upvotes) - (downvotes). We store explicit counts or calculate on fly.
        // Let's calculate: count(up) - count(down)
        $sql = "SELECT c.*, u.nom as author_name,
                (SELECT COUNT(*) FROM votes WHERE comment_id = c.id AND vote_type = 'up') - 
                (SELECT COUNT(*) FROM votes WHERE comment_id = c.id AND vote_type = 'down') as score
                FROM comments c 
                JOIN users u ON c.utilisateur_id = u.id 
                WHERE c.post_id = ? 
                ORDER BY c.date_commentaire ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($contenu, $userId, $postId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO comments (contenu, utilisateur_id, post_id) VALUES (?, ?, ?)");
        return $stmt->execute([$contenu, $userId, $postId]);
    }

    public function update($id, $contenu)
    {
        $stmt = $this->pdo->prepare("UPDATE comments SET contenu = ? WHERE id = ?");
        return $stmt->execute([$contenu, $id]);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT c.*, u.nom as author_name FROM comments c JOIN users u ON c.utilisateur_id = u.id WHERE c.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($term)
    {
        $stmt = $this->pdo->prepare("SELECT c.*, u.nom as author_name FROM comments c JOIN users u ON c.utilisateur_id = u.id WHERE contenu LIKE ? LIMIT 10");
        $stmt->execute(["%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
