<?php

namespace Modules\Supplier\Repositories;

use Modules\Supplier\Models\Supplier;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SupplierRepository
{
    public function all(bool $onlyActive = false)
    {
        return $this->baseQuery($onlyActive)
            ->orderBy('name')
            ->get();
    }

    public function forUser(User $user, bool $onlyActive = false)
    {
        return $this->baseQuery($onlyActive)
            ->whereHas('users', function (Builder $query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->orderBy('name')
            ->get();
    }

    protected function baseQuery(bool $onlyActive = false): Builder
    {
        $query = Supplier::query();

        if ($onlyActive) {
            $query->where('status', true);
        }

        return $query;
    }
}
