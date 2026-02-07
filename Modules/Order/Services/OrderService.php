<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepository;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Modules\Product\Services\ProductService;

class OrderService
{
    public function __construct(
        protected OrderRepository $repository,
        protected ProductService $productService
    ) {}

    public function createFromFrontPayload(array $payload, User $user): Order
    {
        return DB::transaction(function () use ($payload, $user) {
            $supplierId = $payload['supplier']['id'];
            $this->validateSupplierAccess($user, $supplierId);
            $this->validateProductsFromSupplier(
                $payload['products'],
                $supplierId
            );
            $this->validateQuantities($payload['products']);
            $this->validateUnitPrices($payload['products']);
            $normalizedProducts = $this->normalizeProducts(
                $payload['products']
            );
            $order = $this->repository->create([
                'supplier_id' => $supplierId,
                'user_id'     => $user->id,
                'date'        => $payload['date'],
                'observation' => $payload['observation'] ?? null,
                'status'      => 'Pendente',
            ]);

            $this->ensureOrderIsEditable($order);

            foreach ($normalizedProducts as $product) {
                $order->items()->create([
                    'product_id' => $product['id'],
                    'unit_price' => $product['unitPrice'],
                    'quantity'   => $product['quantity'],
                ]);
            }

            return $order->load(['items', 'supplier']);
        });
    }

    public function updateFromFrontPayload(Order $order, array $payload, $user): Order
    {
        return DB::transaction(function () use ($order, $payload, $user) {
            $this->ensureOrderIsEditable($order);
            $this->validateSupplierAccess($user, $order->supplier_id);
            $this->validateProductsFromSupplier(
                $payload['products'],
                $order->supplier_id
            );
            $this->validateQuantities($payload['products']);
            $this->validateUnitPrices($payload['products']);
            $normalizedProducts = $this->normalizeProducts(
                $payload['products']
            );
            if (isset($payload['status'])) {
                if (! in_array($payload['status'], ['Pendente', 'Concluído', 'Cancelado'])) {
                    throw ValidationException::withMessages([
                        'status' => [
                            'Status inválido.'
                        ]
                    ]);
                }
            }

            $order->update([
                'date'        => $payload['date'],
                'observation' => $payload['observation'] ?? null,
                'status'      => $payload['status'] ?? $order->status
            ]);

            $order->items()->delete();

            foreach ($normalizedProducts as $product) {
                $order->items()->create([
                    'product_id' => $product['id'],
                    'unit_price' => $product['unitPrice'],
                    'quantity'   => $product['quantity'],
                ]);
            }

            return $order->load(['items', 'supplier']);
        });
    }

    private function validateProductsFromSupplier(array $products, int $supplierId): void
    {
        $cachedProducts = $this->productService
            ->getBySupplier($supplierId)
            ->keyBy('id');

        foreach ($products as $product) {
            if (! $cachedProducts->has($product['id'])) {
                throw ValidationException::withMessages([
                    'products' => [
                        'Um ou mais produtos não pertencem ao fornecedor selecionado.'
                    ]
                ]);
            }
        }
    }

    private function validateQuantities(array $products): void
    {
        foreach ($products as $product) {
            if ($product['quantity'] <= 0) {
                throw ValidationException::withMessages([
                    'quantity' => [
                        'A quantidade deve ser maior que zero.'
                    ]
                ]);
            }
        }
    }

    private function validateUnitPrices(array $products): void
    {
        foreach ($products as $product) {
            if ($product['unitPrice'] <= 0) {
                throw ValidationException::withMessages([
                    'unitPrice' => [
                        'O preço unitário deve ser maior que zero.'
                    ]
                ]);
            }
        }
    }

    private function ensureOrderIsEditable(Order $order): void
    {
        if (! $order->isEditable()) {
            throw ValidationException::withMessages([
                'order' => [
                    'Este pedido não pode ser alterado porque não está pendente.'
                ]
            ]);
        }
    }

    private function normalizeProducts(array $products): array
    {
        return collect($products)
            ->groupBy('id')
            ->map(function ($group) {
                return [
                    'id'        => $group->first()['id'],
                    'unitPrice' => $group->first()['unitPrice'],
                    'quantity'  => $group->sum('quantity'),
                ];
            })
            ->values()
            ->toArray();
    }

    private function validateSupplierAccess(User $user, int $supplierId): void
    {
        if ($user->type === 'admin') {
            return;
        }
        $hasAccess = $user->suppliers()
            ->where('suppliers.id', $supplierId)
            ->exists();
        if (! $hasAccess) {
            throw ValidationException::withMessages([
                'supplier' => [
                    'Você não tem permissão para criar pedidos para este fornecedor.'
                ]
            ]);
        }
    }

}
