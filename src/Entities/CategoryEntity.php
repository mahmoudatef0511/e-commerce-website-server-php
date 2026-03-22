<?php

namespace App\Entities;

class CategoryEntity extends AbstractEntity
{
    private string $name;

    public function __construct(array $data = [])
    {
        $this->id   = $data['id'] ?? null; 
        $this->name = $data['name'] ?? '';
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}