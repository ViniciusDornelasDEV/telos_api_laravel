<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierUserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = DB::table('users')
            ->where('type', 'seller')
            ->orderBy('id')
            ->get();

        if ($sellers->count() < 2) {
            return;
        }

        $sellerAll  = $sellers[0];
        $sellerFive = $sellers[1];

        $suppliers = DB::table('suppliers')->orderBy('id')->get();

        foreach ($suppliers as $supplier) {
            DB::table('supplier_user')->insert([
                'user_id'     => $sellerAll->id,
                'supplier_id' => $supplier->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

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
