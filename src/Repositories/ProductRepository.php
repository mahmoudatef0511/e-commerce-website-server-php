<?php

namespace App\Repositories;

use App\Model\Product as ProductModel;
use App\Entities\ProductEntity;

class ProductRepository extends AbstractRepository
{
    protected static function getModelClass(): string
    {
        return ProductModel::class;
    }

    protected static function mapToEntity(array $raw): ProductEntity
    {
        $product = new ProductEntity($raw);
        $product->setProductId((int)$raw['product_id']);
        return $product;
    }
}