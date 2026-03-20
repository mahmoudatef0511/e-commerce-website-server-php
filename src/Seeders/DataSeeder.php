<?php

namespace App\Seeders;

use PDO;

class DataSeeder
{
    private PDO $pdo;
    private array $data;

    public function __construct(PDO $pdo, string $jsonFile)
    {
        $this->pdo = $pdo;
        $this->data = json_decode(file_get_contents($jsonFile), true)['data'];
    }

    public function seed(): void
    {
        $this->resetTables();
        $this->insertCategories();
        $this->insertProducts();

        echo "✅ Database populated successfully!\n";
    }

    private function resetTables(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

        $tables = [
            'order_items',
            'orders',
            'prices',
            'attribute_items',
            'attributes',
            'products',
            'categories'
        ];

        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE $table;");
        }

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    }

    private function insertCategories(): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (name) VALUES (:name)"
        );

        foreach ($this->data['categories'] as $cat) {
            $stmt->execute([
                'name' => $cat['name']
            ]);
        }
    }

    private function insertProducts(): void
    {
        $categoryMap = [];
        $stmt = $this->pdo->query("SELECT id, name FROM categories");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoryMap[$row['name']] = $row['id'];
        }

        $productStmt = $this->pdo->prepare("
        INSERT INTO products (id, name, in_stock, description, category_id, brand)
        VALUES (:id, :name, :in_stock, :description, :category_id, :brand)
    ");

        $attributeStmt = $this->pdo->prepare("
        INSERT INTO attributes (product_id, name, type)
        VALUES (:product_id, :name, :type)
    ");

        $attributeItemStmt = $this->pdo->prepare("
        INSERT INTO attribute_items (attribute_id, value, display_value)
        VALUES (:attribute_id, :value, :display_value)
    ");

        $priceStmt = $this->pdo->prepare("
        INSERT INTO prices (product_id, amount, currency)
        VALUES (:product_id, :amount, :currency)
    ");

        $galleryStmt = $this->pdo->prepare("
        INSERT INTO galleries (product_id, image_url)
        VALUES (:product_id, :image_url)
    ");

        foreach ($this->data['products'] as $prod) {

            $productStmt->execute([
                'id' => $prod['id'],
                'name' => $prod['name'],
                'in_stock' => $prod['inStock'] ? 1 : 0,
                'description' => strip_tags($prod['description']),
                'category_id' => $categoryMap[$prod['category']],
                'brand' => $prod['brand']
            ]);

            $dbProductId = $this->pdo->lastInsertId();

            // Insert attributes and attribute items
            foreach ($prod['attributes'] as $attr) {
                $attributeStmt->execute([
                    'product_id' => $dbProductId,
                    'name' => $attr['name'],
                    'type' => $attr['type']
                ]);

                $attributeId = $this->pdo->lastInsertId();

                foreach ($attr['items'] as $item) {
                    $attributeItemStmt->execute([
                        'attribute_id' => $attributeId,
                        'value' => $item['value'],
                        'display_value' => $item['displayValue']
                    ]);
                }
            }

            // Insert prices
            foreach ($prod['prices'] as $price) {
                $priceStmt->execute([
                    'product_id' => $dbProductId,
                    'amount' => $price['amount'],
                    'currency' => $price['currency']['symbol']
                ]);
            }

            // Insert galleries
            if (!empty($prod['gallery'])) {
                foreach ($prod['gallery'] as $imageUrl) {
                    $galleryStmt->execute([
                        'product_id' => $dbProductId,
                        'image_url' => $imageUrl
                    ]);
                }
            }
        }
    }
}
