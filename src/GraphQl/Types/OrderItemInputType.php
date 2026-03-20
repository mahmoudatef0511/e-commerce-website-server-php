<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => Type::nonNull(Type::int()),
                'quantity' => Type::nonNull(Type::int()),
                'selectedOptions' => Type::listOf(new SelectedOptionInputType()),
            ],
        ]);
    }
}