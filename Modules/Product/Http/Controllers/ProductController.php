<?php

namespace Modules\Product\Http\Controllers;

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

        return response()->json(
            ProductResource::collection($products)->resolve(),
            201
        );
    }

    public function insert(InsertProductRequest $request)
    {
        $product = $this->service->insert($request->validated());

        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $updatedProduct = $this->service->update($product, $request->validated());

        return response()->json($updatedProduct, 201);
    }

    public function listBySupplier(Supplier $supplier)
    {
        Gate::authorize('getBySupplier', Product::class);
        $products = $this->service->getBySupplier($supplier->id);
        return response()->json(
            ProductResource::collection($products)->resolve(),
            201
        );
    }
}
