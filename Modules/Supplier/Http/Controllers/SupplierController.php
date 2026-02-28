<?php

namespace Modules\Supplier\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Http\Requests\InsertSupplierRequest;
use Modules\Supplier\Http\Requests\UpdateSupplierRequest;
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
        $onlyActive = $request->boolean('active');
        $suppliers = $this->service->listForUser(
            $request->user(),
            $onlyActive
        );
        $suppliers->load('users');

        return ApiResponse::success(
            SupplierResource::collection($suppliers)->resolve(), 201
        );
    }

    public function insert(InsertSupplierRequest $request)
    {
        $supplier = $this->service->insert($request->validated());
        $supplier->load('users');

        return ApiResponse::success($supplier, 201);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $updatedSupplier = $this->service->update($supplier, $request->validated());
        $updatedSupplier->load('users');

        return ApiResponse::success($updatedSupplier, 201);
    }

}
