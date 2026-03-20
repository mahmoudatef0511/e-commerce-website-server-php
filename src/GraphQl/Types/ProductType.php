<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Types\AttributeType;
use App\GraphQL\Types\PriceType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Product',
            'fields' => [
                'product_id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn($product) => $product->getProductId()
                ],
                'id' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getId()
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getName()
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getBrand()
                ],
                'category' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getCategory()
                ],
                'inStock' => [
                    'type' => Type::boolean(),
                    'resolve' => fn($product) => $product->isInStock()
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getDescription()
                ],
                'attributes' => [
                    'type' => Type::listOf(new AttributeType()),
                    'resolve' => fn($product) => $product->getAttributes()
                ],
                'prices' => [
                    'type' => Type::listOf(new PriceType()),
                    'resolve' => fn($product) => $product->getPrices()
                ],
                'gallery' => [
                    'type' => Type::listOf(Type::string()),
                    'resolve' => fn($product) => $product->getGallery()
                ]
            ]
        ];

        parent::__construct($config);
    }
}
