<?php

namespace App\Model;

use PDO;

class Order extends AbstractModel
{
    public static function fetchAll(): array
    {
        $stmt = self::getDb()->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchById(string $id): ?array
    {
        $stmt = self::getDb()->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        $stmtItems = self::getDb()->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmtItems->execute([':order_id' => $id]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public static function create(array $items, float $total): array
    {
        $db   = self::getDb();
        $stmt = $db->prepare("INSERT INTO orders (total) VALUES ($total)");
        $stmt->execute();
        $orderId = $db->lastInsertId();

        foreach ($items as $item) {
            self::addItem($orderId, $item);
        }

        return self::fetchById($orderId);
    }

    public static function addItem(int $orderId, array $item): void
    {
        $stmt = self::getDb()->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, selected_options)
            VALUES (:order_id, :product_id, :quantity, :selected_options)
        ");
        $stmt->execute([
            ':order_id'        => $orderId,
            ':product_id'      => $item['productId'],
            ':quantity'        => $item['quantity'],
            ':selected_options'=> json_encode($item['selectedOptions'] ?? []),
        ]);
    }
}