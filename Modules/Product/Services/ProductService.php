<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Models\Product;

class ProductService
{
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->all();
    }

    public function insert(array $data): Product
    {
        $product = Product::create($data);
        $this->clearSupplierCache($product->supplier_id);
        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        $this->clearSupplierCache($product->supplier_id);
        return $product->fresh();
    }

    public function getBySupplier(int $supplierId)
    {
        return Cache::remember(
            "products:supplier:{$supplierId}",
            now()->addMinutes(30),
            function () use ($supplierId) {
                return Product::where('supplier_id', $supplierId)
                    ->where('status', true)
                    ->get();
            }
        );
    }

    public function clearSupplierCache(int $supplierId): void
    {
        Cache::forget("products:supplier:{$supplierId}");
    }

}
