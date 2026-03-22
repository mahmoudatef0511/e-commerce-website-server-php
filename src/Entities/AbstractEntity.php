<?php

namespace App\Entities;

abstract class AbstractEntity implements \JsonSerializable
{
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    abstract public function jsonSerialize(): array;
}