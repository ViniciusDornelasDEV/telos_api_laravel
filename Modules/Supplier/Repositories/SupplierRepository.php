<?php

namespace Modules\Supplier\Repositories;

use Modules\Supplier\Models\Supplier;
use Modules\User\Models\User;

class SupplierRepository
{
    public function all()
    {
        return Supplier::orderBy('name')->get();
    }

    public function forUser(User $user)
    {
        return Supplier::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->orderBy('name')
        ->get();
    }
}
