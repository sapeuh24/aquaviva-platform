<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvironmentalMediaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('environmental_media')->insertOrIgnore([
            ['name' => 'Abiótico',                                         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biótico',                                          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Marino - Offshore',                                'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Socioeconómico',                                   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gestión de riesgo',                                'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Zonificación',                                     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Áreas de conservación y protección ambiental',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Áreas de reglamentación especial',                 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Compensación',                                     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Inversión 1%',                                     'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
