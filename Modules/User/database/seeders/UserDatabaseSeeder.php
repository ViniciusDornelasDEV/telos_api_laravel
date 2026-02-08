<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'       => 'Administrador',
                'email'      => 'admin@telos.com',
                'password'   => Hash::make('admin123'),
                'status'     => true,
                'type'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Vendedor João',
                'email'      => 'joao@telos.com',
                'password'   => Hash::make('seller123'),
                'status'     => true,
                'type'       => 'seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Vendedora Maria',
                'email'      => 'maria@telos.com',
                'password'   => Hash::make('seller123'),
                'status'     => true,
                'type'       => 'seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
