<?php
namespace App\Models;

use PDO;

class User extends BaseModel
{

    public function create($nom, $email, $password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
        // Hash password before calling this method or inside? 
        // Best practice: Hash inside controller or service, but for this simple MVC, hashing in controller is common.
        // I will assume the controller passes the hashed password, OR I hash it here.
        // Requirement says: "password_hash and password_verify".
        // Let's expect hashed password.
        return $stmt->execute([$nom, $email, $password]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($term)
    {
        $stmt = $this->pdo->prepare("SELECT id, nom, email FROM users WHERE nom LIKE ? OR email LIKE ? LIMIT 10");
        $stmt->execute(["%$term%", "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
