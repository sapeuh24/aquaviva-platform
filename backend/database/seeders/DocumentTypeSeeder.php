<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('document_types')->insertOrIgnore([
            ['name' => 'Cédula de Ciudadanía',   'code' => 'CC',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cédula de Extranjería',   'code' => 'CE',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pasaporte',               'code' => 'PA',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'NIT',                     'code' => 'NIT',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tarjeta de Identidad',    'code' => 'TI',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'NUIP',                    'code' => 'NUIP', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
