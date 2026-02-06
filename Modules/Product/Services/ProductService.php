<?php

namespace Modules\Product\Services;

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
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }
}
