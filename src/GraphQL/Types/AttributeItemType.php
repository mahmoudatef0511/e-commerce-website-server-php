<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeItem',
            'fields' => [
                'id' => [
                    'type' => Type::int(),
                    'resolve' => fn($item) => $item->getId()
                ],
                'value' => [
                    'type' => Type::string(),
                    'resolve' => fn($item) => $item->getValue()
                ],
                'displayValue' => [
                    'type' => Type::string(),
                    'resolve' => fn($item) => $item->getDisplayValue()
                ],
            ],
        ]);
    }
}
