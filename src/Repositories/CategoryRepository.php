<?php

namespace App\Repositories;

use App\Model\Category as CategoryModel;
use App\Entities\CategoryEntity;

class CategoryRepository extends AbstractRepository
{
    protected static function getModelClass(): string
    {
        return CategoryModel::class;
    }

    protected static function mapToEntity(array $raw): CategoryEntity
    {
        return new CategoryEntity($raw);
    }
}