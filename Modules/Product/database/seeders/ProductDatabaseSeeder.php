<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = DB::table('suppliers')->orderBy('id')->get();
        $suppliers = $suppliers->slice(1);
        foreach ($suppliers as $supplier) {
            $productsCount = rand(3, 5);
            for ($i = 1; $i <= $productsCount; $i++) {
                DB::table('products')->insert([
                    'supplier_id' => $supplier->id,
                    'reference'   => 'REF-' . $supplier->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'name'        => 'Produto ' . $i . ' do ' . $supplier->name,
                    'color'       => $this->randomColor(),
                    'price'       => rand(1000, 15000) / 100, // R$ 10,00 até R$ 150,00
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    private function randomColor(): string
    {
        $colors = [
            'Preto',
            'Branco',
            'Vermelho',
            'Azul',
            'Verde',
            'Amarelo',
            'Cinza',
        ];

        return $colors[array_rand($colors)];
    }
}
