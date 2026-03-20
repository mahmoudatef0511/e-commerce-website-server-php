<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn($category) => $category->getId()
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => fn($category) => $category->getName()
                ],
            ]
        ];

        parent::__construct($config);
    }
}