<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class OrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pega sellers (ignora admin)
        $users = DB::table('users')
            ->where('type', 'seller')
            ->orderBy('id')
            ->get();

        $suppliers = DB::table('suppliers')->orderBy('id')->get();
        $products  = DB::table('products')->orderBy('id')->get();

        if ($users->isEmpty() || $suppliers->isEmpty() || $products->isEmpty()) {
            return; // segurança
        }

        $statuses = ['Pendente', 'Concluído', 'Cancelado'];

        for ($i = 1; $i <= 5; $i++) {

            // escolhe supplier e seller aleatórios
            $supplier = $suppliers->random();
            $user     = $users->random();

            // cria o pedido
            $orderId = DB::table('orders')->insertGetId([
                'supplier_id' => $supplier->id,
                'user_id'     => $user->id,
                'date'        => now()->subDays(rand(0, 30))->toDateString(),
                'observation' => 'Pedido seed #' . $i . ' para ' . $supplier->name,
                'status'      => Arr::random($statuses),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // produtos do fornecedor
            $supplierProducts = $products
                ->where('supplier_id', $supplier->id)
                ->values();

            if ($supplierProducts->isEmpty()) {
                continue; // fornecedor sem produtos
            }

            // de 1 a 3 produtos por pedido
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
