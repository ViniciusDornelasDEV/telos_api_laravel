<?php

namespace Modules\Supplier\Services;

use Modules\Supplier\Repositories\SupplierRepository;
use Modules\User\Models\User;
use Modules\Supplier\Models\Supplier;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $repository
    ) {}

    public function listForUser(User $user)
    {
        if ($user->type === 'admin') {
            return $this->repository->all();
        }

        return $this->repository->forUser($user);
    }

    public function insert(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier->fresh();
    }
}
