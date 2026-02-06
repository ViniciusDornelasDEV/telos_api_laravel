<?php

namespace Modules\Suppliers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Fornecedor Alpha LTDA',
                'cnpj' => '12.345.678/0001-90',
                'cep' => '01001-000',
                'address' => 'Rua das Flores, 100 - Centro',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beta Distribuidora',
                'cnpj' => '23.456.789/0001-01',
                'cep' => '02002-000',
                'address' => 'Av. Brasil, 2500 - Jardim América',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gamma Comércio',
                'cnpj' => '34.567.890/0001-12',
                'cep' => '03003-000',
                'address' => 'Rua Industrial, 45 - Distrito',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Delta Fornecimentos',
                'cnpj' => '45.678.901/0001-23',
                'cep' => '04004-000',
                'address' => 'Av. Paulista, 900',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Epsilon Importadora',
                'cnpj' => '56.789.012/0001-34',
                'cep' => '05005-000',
                'address' => 'Rua do Porto, 321',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zeta Atacadista',
                'cnpj' => '67.890.123/0001-45',
                'cep' => '06006-000',
                'address' => 'Av. Central, 1500',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eta Comercial',
                'cnpj' => '78.901.234/0001-56',
                'cep' => '07007-000',
                'address' => 'Rua São João, 88',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Theta Distribuição',
                'cnpj' => '89.012.345/0001-67',
                'cep' => '08008-000',
                'address' => 'Av. das Nações, 400',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Iota Suprimentos',
                'cnpj' => '90.123.456/0001-78',
                'cep' => '09009-000',
                'address' => 'Rua Projetada, 10',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kappa Fornecedor',
                'cnpj' => '01.234.567/0001-89',
                'cep' => '10010-000',
                'address' => 'Av. Norte, 700',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lambda Comércio',
                'cnpj' => '11.223.344/0001-90',
                'cep' => '11011-000',
                'address' => 'Rua Sul, 55',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mu Distribuidora',
                'cnpj' => '22.334.455/0001-01',
                'cep' => '12012-000',
                'address' => 'Av. Leste, 180',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nu Importações',
                'cnpj' => '33.445.566/0001-12',
                'cep' => '13013-000',
                'address' => 'Rua Oeste, 999',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Xi Industrial',
                'cnpj' => '44.556.677/0001-23',
                'cep' => '14014-000',
                'address' => 'Av. Fabril, 123',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Omicron Serviços',
                'cnpj' => '55.667.788/0001-34',
                'cep' => '15015-000',
                'address' => 'Rua das Indústrias, 456',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
