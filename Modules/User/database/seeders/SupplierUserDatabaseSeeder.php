<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierUserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Busca os vendedores
        $sellers = DB::table('users')
            ->where('type', 'seller')
            ->orderBy('id')
            ->get();

        if ($sellers->count() < 2) {
            return; // segurança
        }

        $sellerAll  = $sellers[0]; // terá TODAS as empresas
        $sellerFive = $sellers[1]; // terá apenas 5

        // Busca todos os fornecedores
        $suppliers = DB::table('suppliers')->orderBy('id')->get();

        // 🔹 Vendedor 1 → todas as empresas
        foreach ($suppliers as $supplier) {
            DB::table('supplier_user')->insert([
                'user_id'     => $sellerAll->id,
                'supplier_id' => $supplier->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // 🔹 Vendedor 2 → apenas 5 empresas
        $suppliers->take(5)->each(function ($supplier) use ($sellerFive) {
            DB::table('supplier_user')->insert([
                'user_id'     => $sellerFive->id,
                'supplier_id' => $supplier->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });
    }
}
