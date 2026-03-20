<?php

namespace App\Entities;

class AttributeItemEntity implements \JsonSerializable
{
    private int $id;
    private string $value;
    private string $displayValue;

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
    public function getValue(): string
    {
        return $this->value;
    }

    public function setDisplayValue(string $displayValue): void
    {
        $this->displayValue = $displayValue;
    }
    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'displayValue' => $this->displayValue,
        ];
    }
}
