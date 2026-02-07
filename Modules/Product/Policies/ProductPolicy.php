<?php

namespace Modules\Product\Policies;

use Modules\User\Models\User;
use Modules\Product\Models\Product;

class ProductPolicy
{
    public function index(User $user): bool
    {
        return in_array($user->type, ['admin', 'seller']);
    }

    public function getBySupplier(User $user): bool
    {
        return in_array($user->type, ['admin', 'seller']);
    }
    
    public function insert(User $user): bool
    {
        return in_array($user->type, ['admin', 'seller']);

    }

    public function update(User $user): bool
    {
        return in_array($user->type, ['admin', 'seller']);
    }
}
