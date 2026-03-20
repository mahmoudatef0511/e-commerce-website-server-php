<?php

namespace App\Repositories;

use App\Model\Category as CategoryModel;
use App\Entities\CategoryEntity;

class CategoryRepository
{
    public static function all(): array
    {
        $rawCategories = CategoryModel::fetchAll();
        $entities = self::mapToEntities($rawCategories);
        return $entities;
    }

    public static function byId(string $id): ?CategoryEntity
    {
        $rawCategory = CategoryModel::fetchById($id);
        if (!$rawCategory) return null;
        $entities = self::mapToEntities([$rawCategory]);
        return $entities[0] ?? null;
    }

    private static function mapToEntities(array $rawCategories): array
    {
        $entities = [];
        foreach ($rawCategories as $raw) {
            $category = new CategoryEntity($raw);
            $entities[] = $category;
        }
        return $entities;
    }
}
