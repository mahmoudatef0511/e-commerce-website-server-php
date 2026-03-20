<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class SelectedOptionType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SelectedOption',
            'fields' => [
                'name' => Type::string(),
                'value' => Type::string(),
            ],
        ]);
    }
}