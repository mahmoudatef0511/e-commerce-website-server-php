<?php

namespace App\GraphQL\Resolvers;

use App\Repositories\OrderRepository;

class OrderResolver
{
    
    public static function createOrder($root, $args)
    {
        return OrderRepository::create($args['items'], $args['total']);
    }

    public static function order($root, $args)
    {
        if (empty($args['id'])) {
            return null;
        }

        return OrderRepository::byId($args['id']);
    }
}