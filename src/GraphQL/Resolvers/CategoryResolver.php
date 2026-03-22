<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\CategoryRepository;

class CategoryResolver extends AbstractResolver
{
    protected static function getRepositoryClass(): string
    {
        return CategoryRepository::class;
    }

    public static function resolveCategories($root, $args): array
    {
        if (!empty($args['id'])) {
            $category = self::byId($args['id']);
            return $category ? [$category] : [];
        }

        return self::all();
    }
}