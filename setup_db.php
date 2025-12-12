<?php
require_once 'app/Config/Database.php';

use App\Config\Database;

try {
    $pdo = Database::getInstance()->getConnection();
    $sql = file_get_contents('sql/schema.sql');

    // Execute multiple queries
    $pdo->exec($sql);

    echo "Database Schema Imported Successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
