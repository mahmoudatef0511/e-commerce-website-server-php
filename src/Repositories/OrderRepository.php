<?php

namespace App\Repositories;

use App\Model\Order as OrderModel;
use App\Entities\OrderEntity;
use App\Entities\OrderItemEntity;

class OrderRepository extends AbstractRepository
{
    protected static function getModelClass(): string
    {
        return OrderModel::class;
    }

    protected static function mapToEntity(array $raw): OrderEntity
    {
        $order = new OrderEntity($raw);

        $items = [];
        foreach ($raw['items'] ?? [] as $itemRaw) {
            $items[] = new OrderItemEntity($itemRaw);
        }
        $order->setItems($items);

        return $order;
    }

    /**
     * create() is specific to orders — no equivalent in AbstractRepository.
     * It delegates to the Model and maps the result to an entity.
     */
    public static function create(array $items, float $total): OrderEntity
    {
        $rawOrder = OrderModel::create($items, $total);
        return static::mapToEntity($rawOrder);
    }
}