<?php

namespace Modules\Supplier\Policies;

use Modules\User\Models\User;
use Modules\Supplier\Models\Supplier;

class SupplierPolicy
{
    public function index(User $user): bool
    {
        return in_array($user->type, ['admin', 'seller']);
    }
    
    public function insert(User $user): bool
    {
        return $user->type === 'admin';
    }

    public function update(User $user): bool
    {
        return $user->type === 'admin';
    }
}
