<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Product\Http\Requests\InsertProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Services\ProductService;
use Modules\Product\Models\Product;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Supplier\Models\Supplier;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('index', Product::class);
        $products = $this->service->list();

        return ApiResponse::success(
            ProductResource::collection($products)->resolve(),
            201
        );
    }

    public function insert(InsertProductRequest $request)
    {
        $product = $this->service->insert($request->validated());

        return ApiResponse::success($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $updatedProduct = $this->service->update($product, $request->validated());

        return ApiResponse::success($updatedProduct, 201);
    }

    public function listBySupplier(Supplier $supplier)
    {
        Gate::authorize('getBySupplier', Product::class);
        $products = $this->service->getBySupplier($supplier->id);
        return ApiResponse::success(
            ProductResource::collection($products)->resolve(),
            201
        );
    }
}
