<?php 

namespace App\Model;

use PDO;
use App\Core\Database;

class Category {

    public static function fetchAll(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchById(string $id): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}