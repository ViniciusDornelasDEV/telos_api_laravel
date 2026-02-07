<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Product\Services\ProductService;
use Modules\Product\Models\Product;
use Modules\Product\Http\Resources\ProductResource;

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

    public function insert(Request $request)
    {
        Gate::authorize('insert', Product::class);
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference'   => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'color'       => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'status'   => 'required|in:active,inactive',
        ]);


        $product = $this->service->insert($data);

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', Product::class);
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference'   => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'color'       => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'status'   => 'required|in:active,inactive',
        ]);

        $updatedProduct = $this->service->update($product, $data);

        return response()->json($updatedProduct, 201);
    }
}
