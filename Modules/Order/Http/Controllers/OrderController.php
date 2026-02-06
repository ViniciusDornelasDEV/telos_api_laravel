<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Services\OrderService;
use Modules\Order\Http\Resources\OrderResource;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $service
    ) {}

    /**
     * Listagem
     */
    public function index(): JsonResponse
    {
        $orders = Order::with('items')
            ->orderByDesc('date')
            ->get();

        return response()->json(
            OrderResource::collection($orders)
        );
    }

    /**
     * Criação
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'date'                 => ['required', 'date'],
            'supplier.id'          => ['required', 'exists:suppliers,id'],
            'products'             => ['required', 'array', 'min:1'],
            'products.*.id'        => ['required', 'exists:products,id'],
            'products.*.unitPrice' => ['required', 'numeric', 'min:0'],
            'products.*.quantity'  => ['required', 'integer', 'min:1'],
            'observation'          => ['nullable', 'string'],
        ]);

        $order = $this->service->createFromFrontPayload(
            $request->all(),
            auth()->user()
        );

        return response()->json(
            new OrderResource($order),
            201
        );
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'date'                 => ['required', 'date'],
            'products'             => ['required', 'array', 'min:1'],
            'products.*.id'        => ['required', 'exists:products,id'],
            'products.*.unitPrice' => ['required', 'numeric'],
            'products.*.quantity'  => ['required', 'integer'],
            'observation'          => ['nullable', 'string'],
        ]);

        $order = $this->service->updateFromFrontPayload(
            $order,
            $request->all(),
            auth()->user()
        );

        return response()->json(
            new OrderResource($order)
        );
    }
}
