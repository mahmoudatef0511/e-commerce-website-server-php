<?php

namespace App\Model;

use PDO;

class Attribute extends AbstractModel
{
    public static function fetchAll(): array
    {
        $stmt = self::getDb()->query("SELECT * FROM attributes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchById(string $id): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM attributes WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function fetchByProduct(string $productId): array
    {
        $stmt = self::getDb()->prepare("
            SELECT a.*, ai.value
            FROM attributes a
            JOIN attribute_items ai ON ai.attribute_id = a.id
            WHERE a.product_id = :id
        ");
        $stmt->bindValue(':id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}