<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Modules\Suppliers\database\seeders\SuppliersDatabaseSeeder::class,
            \Modules\Product\database\seeders\ProductDatabaseSeeder::class,
            \Modules\User\database\seeders\UserDatabaseSeeder::class,
            \Modules\User\database\seeders\SupplierUserDatabaseSeeder::class,
            \Modules\Order\database\seeders\OrderDatabaseSeeder::class,
        ]);
    }
}
