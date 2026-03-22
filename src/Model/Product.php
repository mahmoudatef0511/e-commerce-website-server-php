<?php

namespace App\Model;

use PDO;

class Product extends AbstractModel
{
    public static function fetchAll(): array
    {
        $stmt = self::getDb()->query("
            SELECT p.*, c.name AS category
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
        ");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = self::attachAttributes($products);
        $products = self::attachGalleries($products);
        return self::attachPrices($products);
    }

    public static function fetchById(string $id): ?array
    {
        $stmt = self::getDb()->prepare("
            SELECT p.*, c.name AS category
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :productId
        ");
        $stmt->bindValue(':productId', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            return null;
        }

        $product = self::attachAttributes([$product]);
        $product = self::attachGalleries($product);
        $product = self::attachPrices($product);

        return $product[0] ?? null;
    }

    public static function fetchByCategory(?string $categoryName): array
    {
        $db = self::getDb();

        if ($categoryName) {
            $stmt = $db->prepare("
                SELECT p.*, c.name AS category
                FROM products p
                JOIN categories c ON p.category_id = c.id
                WHERE c.name = :category_name
            ");
            $stmt->bindValue(':category_name', $categoryName, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->query("
                SELECT p.*, c.name AS category
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
            ");
        }

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = self::attachAttributes($products);
        $products = self::attachGalleries($products);
        return self::attachPrices($products);
    }

    // ── Private helpers ───────────────────────────────────────────

    private static function attachAttributes(array $products): array
    {
        if (empty($products)) return [];

        $db         = self::getDb();
        $productIds = array_column($products, 'product_id');
        $inQuery    = implode(',', array_fill(0, count($productIds), '?'));

        $stmt = $db->prepare("
            SELECT a.*, ai.id AS item_id, ai.value, ai.display_value
            FROM attributes a
            LEFT JOIN attribute_items ai ON ai.attribute_id = a.id
            WHERE a.product_id IN ($inQuery)
        ");
        foreach ($productIds as $k => $id) {
            $stmt->bindValue($k + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $attributesMap = [];
        foreach ($rows as $row) {
            $prodId = $row['product_id'];
            $attrId = $row['id'];

            if (!isset($attributesMap[$prodId][$attrId])) {
                $attributesMap[$prodId][$attrId] = [
                    'id'    => $attrId,
                    'name'  => $row['name'],
                    'type'  => $row['type'],
                    'items' => [],
                ];
            }

            if ($row['item_id']) {
                $attributesMap[$prodId][$attrId]['items'][] = [
                    'id'           => $row['item_id'],
                    'value'        => $row['value'],
                    'displayValue' => $row['display_value'],
                ];
            }
        }

        foreach ($products as &$product) {
            $product['attributes'] = array_values($attributesMap[$product['product_id']] ?? []);
        }

        return $products;
    }

    private static function attachGalleries(array $products): array
    {
        if (empty($products)) return [];

        $stmt = self::getDb()->prepare("SELECT image_url FROM galleries WHERE product_id = ?");

        foreach ($products as &$product) {
            $stmt->execute([$product['product_id']]);
            $product['gallery'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $products;
    }

    private static function attachPrices(array $products): array
    {
        if (empty($products)) return [];

        $stmt = self::getDb()->prepare("SELECT amount, currency FROM prices WHERE product_id = ?");

        foreach ($products as &$product) {
            $stmt->execute([$product['product_id']]);
            $product['prices'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $products;
    }
}