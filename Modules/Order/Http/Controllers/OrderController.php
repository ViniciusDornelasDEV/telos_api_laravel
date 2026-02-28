<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Services\OrderService;
use Modules\Order\Http\Resources\OrderResource;
use Modules\Order\Models\Order;
use Modules\Order\Jobs\SendDailyOrderReportJob;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $service
    ) {}

    public function index(): JsonResponse
    {
        $orders = Order::with(['items', 'supplier'])
            ->orderByDesc('date')
            ->get();

        return response()->json(
            OrderResource::collection($orders)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'date'                 => ['required', 'date'],
            'supplier.id'          => ['required', 'exists:suppliers,id'],
            'products'             => ['required', 'array', 'min:1'],
            'products.*.id'        => ['required', 'exists:products,id'],
            'products.*.unitPrice' => ['required', 'numeric', 'min:0'],
            'products.*.quantity'  => ['required', 'integer', 'min:1'],
            'status'               => ['nullable', 'in:Pendente,Concluído,Cancelado'],
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

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $order = $this->service->updateFromFrontPayload(
            $order,
            $request->validated(),
            auth()->user()
        );

        return response()->json(
            new OrderResource($order)
        );
    }

    public function sendDailyReport(): JsonResponse
    {
        SendDailyOrderReportJob::dispatch(
            auth()->user()->email
        );

        return response()->json([
            'message' => 'Relatório enviado para seu email.'
        ]);
    }
    
}
