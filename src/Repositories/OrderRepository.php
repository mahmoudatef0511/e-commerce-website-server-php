<?php

namespace App\Repositories;

use App\Model\Order as OrderModel;
use App\Entities\OrderEntity;
use App\Entities\OrderItemEntity;

class OrderRepository
{
    public static function create(array $items, float $total): OrderEntity
    {
        $rawOrder = OrderModel::create($items, $total);
        return self::mapToEntity($rawOrder);
    }

    public static function byId(string $id): ?OrderEntity
    {
        $rawOrder = OrderModel::fetchById($id);
        if (!$rawOrder) return null;

        return self::mapToEntity($rawOrder);
    }

    private static function mapToEntity(array $raw): OrderEntity
    {
        $order = new OrderEntity($raw);

        $items = [];
        foreach ($raw['items'] ?? [] as $itemRaw) {
            $items[] = new OrderItemEntity($itemRaw);
        }

        $order->setItems($items);

        return $order;
    }
}