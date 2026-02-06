<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function findWithItems(int $id): Order
    {
        return Order::with('items')->findOrFail($id);
    }
}
