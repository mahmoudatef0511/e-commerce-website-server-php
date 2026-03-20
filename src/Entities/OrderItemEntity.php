<?php

namespace App\Entities;

class OrderItemEntity implements \JsonSerializable
{
    private ?int $id;
    private ?int $order_id;
    private ?int $product_id;
    private int $quantity;
    private array $selected_options;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->order_id = $data['order_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->quantity = $data['quantity'] ?? 0;

        // decode JSON string if needed
        if (isset($data['selected_options']) && is_string($data['selected_options'])) {
            $this->selected_options = json_decode($data['selected_options'], true) ?? [];
        } else {
            $this->selected_options = $data['selected_options'] ?? [];
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSelectedOptions(): array
    {
        return $this->selected_options;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'selected_options' => $this->selected_options,
        ];
    }
}