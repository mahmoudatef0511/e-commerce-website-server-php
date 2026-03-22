<?php

namespace App\Model;

use PDO;

class Price extends AbstractModel
{
    public static function fetchAll(): array
    {
        $stmt = self::getDb()->query("SELECT * FROM prices");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchById(string $id): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM prices WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function fetchByProduct(string $productId): array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM prices WHERE product_id = :id");
        $stmt->bindValue(':id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}