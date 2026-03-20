<?php

namespace App\Entities;

class AttributeEntity implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $type;

    /** @var AttributeItemEntity[] */
    private array $items = [];

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
    public function getType(): string
    {
        return $this->type;
    }

    public function addItem(AttributeItemEntity $item): void
    {
        $this->items[] = $item;
    }
    public function getItems(): array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'items' => array_map(fn($item) => $item->jsonSerialize(), $this->items),
        ];
    }
}
