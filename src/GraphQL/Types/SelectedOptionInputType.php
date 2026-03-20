<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class SelectedOptionInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SelectedOptionInput',
            'fields' => [
                'name' => Type::nonNull(Type::string()),
                'value' => Type::nonNull(Type::string()),
            ],
        ]);
    }
}