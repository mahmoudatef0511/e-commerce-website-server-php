<?php

namespace App\GraphQL\Resolvers;

abstract class AbstractResolver
{
    /**
     * Each resolver declares which Repository class it delegates to.
     */
    abstract protected static function getRepositoryClass(): string;

    /**
     * Fetch all records via the repository.
     */
    public static function all(): array
    {
        return (static::getRepositoryClass())::all();
    }

    /**
     * Fetch a single record by ID via the repository.
     */
    public static function byId(string $id): ?object
    {
        return (static::getRepositoryClass())::byId($id);
    }
}