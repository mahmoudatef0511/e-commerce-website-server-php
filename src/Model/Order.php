<?php
namespace App\Model;

use PDO;
use App\Core\Database;

class Order
{
    public static function create(array $items, float $total): array
    {
        $db = Database::getInstance();

        // Insert the order
        $stmt = $db->prepare("INSERT INTO orders (total) VALUES ($total)");
        $stmt->execute();
        $orderId = $db->lastInsertId();

        // Insert each order item using addItem()
        foreach ($items as $item) {
            self::addItem($orderId, $item);
        }

        // Return the full order
        return self::fetchById($orderId);
    }

    public static function addItem(int $orderId, array $item): void
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, selected_options)
            VALUES (:order_id, :product_id, :quantity, :selected_options)
        ");

        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['productId'],
            ':quantity' => $item['quantity'],
            ':selected_options' => json_encode($item['selectedOptions'] ?? []),
        ]);
    }

    public static function fetchById(string $id): array
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return [];

        $stmtItems = $db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmtItems->execute([':order_id' => $id]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }
}
