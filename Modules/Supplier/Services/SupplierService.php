<?php

namespace Modules\Supplier\Services;

use Illuminate\Support\Facades\DB;
use Modules\Supplier\Repositories\SupplierRepository;
use Modules\User\Models\User;
use Modules\Supplier\Models\Supplier;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $repository
    ) {}

    public function listForUser(User $user, bool $onlyActive = false)
    {
        if ($user->type === 'admin') {
            return $this->repository->all($onlyActive);
        }

        return $this->repository->forUser($user, $onlyActive);
    }

    public function insert(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        return DB::transaction(function () use ($supplier, $data) {

            // 1️⃣ Atualiza dados básicos
            $supplier->update([
                'name'    => $data['name'],
                'cnpj'    => $data['cnpj'],
                'cep'     => $data['cep'],
                'address' => $data['address'],
                'status'  => $data['status'],
            ]);

            // 2️⃣ Sincroniza vendedores (pivot supplier_user)
            if (array_key_exists('sellers', $data)) {
                $supplier->users()->sync($data['sellers']);
            }

            return $supplier;
        });
    }
}
