<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Modules\Suppliers\Database\Seeders\SuppliersDatabaseSeeder::class,
            // futuros:
            // \Modules\User\Database\Seeders\UsersDatabaseSeeder::class,
            // \Modules\Product\Database\Seeders\ProductsDatabaseSeeder::class,
        ]);
    }
}
