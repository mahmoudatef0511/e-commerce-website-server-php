<?php

namespace App\Entities;

class OrderEntity extends AbstractEntity
{
    private string $created_at;
    private float  $total;
    private array  $items = [];

    public function __construct(array $data = [])
    {
        $this->id         = $data['id'] ?? null;
        $this->total      = $data['total'] ?? 0.0;
        $this->created_at = (string)($data['created_at'] ?? date('Y-m-d H:i:s'));

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $this->items[] = $item instanceof OrderItemEntity
                    ? $item
                    : new OrderItemEntity($item);
            }
        }
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function addItem(OrderItemEntity $item): void
    {
        $this->items[] = $item;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'total'      => $this->total,
            'created_at' => $this->created_at,
            'items'      => $this->items,
        ];
    }
}