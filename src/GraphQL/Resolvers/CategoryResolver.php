<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\CategoryRepository;

class CategoryResolver
{
    public static function resolveCategories($root, $args)
    {
        if (!empty($args['id'])) {
            return self::byId($args['id']);
        }

        return self::all();
    }

    public static function all() : array
    {
        return CategoryRepository::all();
    }

    public static function byId(string $id): array
    {
        $category = CategoryRepository::byId($id);
        return $category ? [$category] : [];
    }

}