<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Services\SupplierService;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $service
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('index', Supplier::class);
        $suppliers = $this->service->listForUser(
            $request->user()
        );

        return response()->json(
            SupplierResource::collection($suppliers)->resolve()
);
    }

    public function insert(Request $request)
    {
        Gate::authorize('insert', Supplier::class);
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'cnpj'    => 'required|string|size:18|unique:suppliers,cnpj',
            'cep'     => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'status'  => 'required|in:active,inactive',
        ]);

        $supplier = $this->service->insert($data);

        return response()->json($supplier, 201);
    }

    public function update(Request $request, Supplier $supplier)
    {
        Gate::authorize('update', Supplier::class);
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'cnpj'    => 'required|string|size:18|unique:suppliers,cnpj,' . $supplier->id,
            'cep'     => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'status'  => 'required|in:active,inactive',
        ]);

        $updatedSupplier = $this->service->update($supplier, $data);

        return response()->json($updatedSupplier);
    }

}
