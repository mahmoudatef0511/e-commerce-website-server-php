<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\ProductRepository;

class ProductResolver extends AbstractResolver
{
    protected static function getRepositoryClass(): string
    {
        return ProductRepository::class;
    }

    public static function resolveProducts($root, $args): array
    {
        if (!empty($args['id'])) {
            return [self::byId($args['id'])];
        }

        if (!empty($args['category'])) {
            return self::byCategory($args['category']);
        }

        return self::all();
    }

    public static function byCategory(?string $category): array
    {
        $all = self::all();
        if (!$category || $category === 'all') return $all;
        return array_filter($all, fn($p) => strtolower($p->getCategory()) === strtolower($category));
    }
}