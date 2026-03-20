<?php

namespace App\Model;

use PDO;
use App\core\Database;

class Attribute {

    public static function fetchByProduct(string $productId): array {
        $db = Database::getInstance();

        $stmt = $db->prepare("
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