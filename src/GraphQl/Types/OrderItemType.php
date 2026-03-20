<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderItem',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => fn($item) => $item->getId()
                ],
                'product_id' => [
                    'type' => Type::int(),
                    'resolve' => fn($item) => $item->getProductId()
                ],
                'quantity' => [
                    'type' => Type::int(),
                    'resolve' => fn($item) => $item->getQuantity()
                ],
                'selectedOptions' => [
                    'type' => Type::listOf(new SelectedOptionType()),
                    'resolve' => fn($item) => $item->getSelectedOptions()
                ],
            ]
        ];
        parent::__construct($config);
    }
}
