<?php

namespace App\Repositories;

abstract class AbstractRepository
{
    /**
     * Each repository declares which Model class it works with.
     */
    abstract protected static function getModelClass(): string;

    /**
     * Each repository declares how to convert a raw DB array into an Entity.
     */
    abstract protected static function mapToEntity(array $raw): object;

    /**
     * Fetch all records and map them to entities.
     */
    public static function all(): array
    {
        $modelClass = static::getModelClass();
        $rawItems   = $modelClass::fetchAll();
        return array_map([static::class, 'mapToEntity'], $rawItems);
    }

    /**
     * Fetch a single record by ID and map it to an entity.
     */
    public static function byId(string $id): ?object
    {
        $modelClass = static::getModelClass();
        $raw        = $modelClass::fetchById($id);
        return $raw ? static::mapToEntity($raw) : null;
    }
}