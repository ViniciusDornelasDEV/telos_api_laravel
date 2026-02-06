<?php

namespace Modules\User\Policies;

use Modules\User\Models\User;

class UserPolicy
{
    public function index(User $user): bool
    {
        return $user->type === 'admin';
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
