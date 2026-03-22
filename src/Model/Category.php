<?php

namespace App\Model;

use PDO;

class Category extends AbstractModel
{
    public static function fetchAll(): array
    {
        $stmt = self::getDb()->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchById(string $id): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}