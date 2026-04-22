<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonitoringPhaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('monitoring_phases')->insertOrIgnore([
            ['name' => 'Pre-construcción',              'order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Constructiva',                  'order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Operativa',                     'order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Desmantelamiento y abandono',   'order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Post-cierre',                   'order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
