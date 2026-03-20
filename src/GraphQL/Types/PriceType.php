<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'amount' => [
                    'type' => Type::float(),
                    'resolve' => fn($price) => $price->getAmount()
                ],
                'currency' => [
                    'type' => Type::string(),
                    'resolve' => fn($price) => $price->getCurrency()
                ],
            ],
        ]);
    }
}
