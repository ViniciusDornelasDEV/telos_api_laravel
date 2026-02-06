<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class OrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')
            ->where('type', 'seller')
            ->orderBy('id')
            ->get();

        $suppliers = DB::table('suppliers')->orderBy('id')->get();
        $products  = DB::table('products')->orderBy('id')->get();

        if ($users->isEmpty() || $suppliers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['Pendente', 'Concluído', 'Cancelado'];

        for ($i = 1; $i <= 5; $i++) {

            $supplier = $suppliers->random();
            $user     = $users->random();

            $orderId = DB::table('orders')->insertGetId([
                'supplier_id' => $supplier->id,
                'user_id'     => $user->id,
                'date'        => now()->subDays(rand(0, 30))->toDateString(),
                'observation' => 'Pedido seed #' . $i . ' para ' . $supplier->name,
                'status'      => Arr::random($statuses),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $supplierProducts = $products
                ->where('supplier_id', $supplier->id)
                ->values();

            if ($supplierProducts->isEmpty()) {
                continue;
            }

            $items = $supplierProducts->random(
                min(rand(3, 6), $supplierProducts->count())
            );

            foreach ($items as $product) {
                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'product_id'=> $product->id,
                    'unit_price'=> $product->price,
                    'quantity'  => rand(1, 5),
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            }
        }
    }
}
