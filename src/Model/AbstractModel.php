<?php

namespace App\Model;

use PDO;
use App\Core\Database;

abstract class AbstractModel
{
    /**
     * Shared DB access for all models.
     * Child models call self::getDb() instead of repeating Database::getInstance().
     */
    protected static function getDb(): PDO
    {
        return Database::getInstance();
    }

    /**
     * Every model must be able to fetch all its records.
     */
    abstract public static function fetchAll(): array;

    /**
     * Every model must be able to fetch a single record by ID.
     */
    abstract public static function fetchById(string $id): ?array;
}