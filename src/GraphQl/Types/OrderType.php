<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn($order) => $order->getId()
                ],
                'createdAt' => [
                    'type' => Type::string(),
                    'resolve' => fn($order) => $order->getCreatedAt()
                ],
                'total' => [
                    'type' => Type::float(),
                    'resolve' => fn($order) => $order->getTotal()
                ],
                'items' => [
                    'type' => Type::listOf(new OrderItemType()),
                    'resolve' => fn($order) => $order->getItems()
                ]
            ],
        ];
        parent::__construct($config);
    }
}
