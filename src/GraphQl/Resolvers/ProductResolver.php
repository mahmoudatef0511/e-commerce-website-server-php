<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\ProductRepository;

class ProductResolver
{
    public static function resolveProducts($root, $args)
    {
        if (!empty($args['id'])) {
            return [self::byId($args['id'])];
        }

        if (!empty($args['category'])) {
            return self::byCategory($args['category']);
        }

        return self::all();
    }

    public static function all(): array
    {
        return ProductRepository::all();
    }

    public static function byId(string $id)
    {
        return ProductRepository::byId($id);
    }

    public static function byCategory(?string $category): array
    {
        $all = ProductRepository::all();
        if (!$category || $category === "all") return $all;
        return array_filter($all, fn($p) => strtolower($p->getCategory()) === strtolower($category));
    }
}
