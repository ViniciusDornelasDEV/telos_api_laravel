<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;

class ProductRepository

{
    public function all()
    {
        return Product::orderBy('name')->get();
    }
}
