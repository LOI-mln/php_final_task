<?php
namespace App\Models;

use PDO;

class Post extends BaseModel
{

    public function getAll()
    {
        // Fetch posts with author name and like count
        $sql = "SELECT p.*, u.nom as author_name, 
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count
                FROM posts p 
                JOIN users u ON p.utilisateur_id = u.id 
                ORDER BY p.date_publication DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($titre, $contenu, $userId)
    {
        $stmt = $this->pdo->prepare("INSERT INTO posts (titre, contenu, utilisateur_id) VALUES (?, ?, ?)");
        return $stmt->execute([$titre, $contenu, $userId]);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT p.*, u.nom as author_name FROM posts p JOIN users u ON p.utilisateur_id = u.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $titre, $contenu)
    {
        $stmt = $this->pdo->prepare("UPDATE posts SET titre = ?, contenu = ? WHERE id = ?");
        return $stmt->execute([$titre, $contenu, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search($term)
    {
        $stmt = $this->pdo->prepare("SELECT p.*, u.nom as author_name FROM posts p JOIN users u ON p.utilisateur_id = u.id WHERE titre LIKE ? OR contenu LIKE ? LIMIT 10");
        $stmt->execute(["%$term%", "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
