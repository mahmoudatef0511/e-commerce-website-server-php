<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\OrderRepository;

class OrderResolver extends AbstractResolver
{
    protected static function getRepositoryClass(): string
    {
        return OrderRepository::class;
    }

    public static function createOrder($root, $args): object
    {
        return OrderRepository::create($args['items'], $args['total']);
    }

    public static function order($root, $args): ?object
    {
        if (empty($args['id'])) {
            return null;
        }

        return self::byId($args['id']);
    }
}