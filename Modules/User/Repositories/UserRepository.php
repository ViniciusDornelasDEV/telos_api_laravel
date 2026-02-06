<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepository

{
    public function all()
    {
        return User::orderBy('name')->get();
    }
}
