<?php

namespace App\Repositories;

use App\Model\Product as ProductModel;
use App\Entities\ProductEntity;

class ProductRepository
{
    public static function all(): array
    {
        $rawProducts = ProductModel::fetchAll();
        $entities = self::mapToEntities($rawProducts);
        return $entities;
    }

    public static function byId(string $id): ?ProductEntity
    {
        $rawProduct = ProductModel::fetchById($id);
        if (!$rawProduct) return null;
        $entities = self::mapToEntities([$rawProduct]);
        return $entities[0] ?? null;
    }

    private static function mapToEntities(array $rawProducts): array
    {
        $entities = [];
        foreach ($rawProducts as $raw) {
            $product = new ProductEntity($raw);
            $product->setProductId((int)$raw['product_id']);
            $entities[] = $product;
        }
        return $entities;
    }
}
