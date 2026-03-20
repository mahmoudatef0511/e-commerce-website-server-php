<?php 

namespace App\Model;

use PDO;
use App\Core\Database;

class Price {

    public static function fetchByProduct(string $productId): array {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM prices WHERE product_id = :id");
        $stmt->bindValue(':id', $productId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}