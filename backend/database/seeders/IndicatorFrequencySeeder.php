<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndicatorFrequencySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('indicator_frequencies')->insertOrIgnore([
            ['name' => 'Mensual',               'months_interval' => 1,    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bimensual',             'months_interval' => 2,    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trimestral',            'months_interval' => 3,    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Semestral',             'months_interval' => 6,    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Anual',                 'months_interval' => 12,   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bienal',                'months_interval' => 24,   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Única vez',             'months_interval' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permanente',            'months_interval' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Según requerimiento',   'months_interval' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
