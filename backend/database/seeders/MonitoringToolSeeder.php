<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonitoringToolSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('monitoring_tools')->insertOrIgnore([
            ['name' => 'Licencia ambiental',                                        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de manejo ambiental',                                  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de seguimiento y monitoreo',                           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de gestión del riesgo',                                'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de desmantelamiento y abandono',                       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de inversión de no menos del 1%',                      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de compensación del medio biótico',                    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permiso de vertimientos',                                   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Concesión aguas subterráneas',                              'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Concesión aguas superficiales',                             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permiso para aprovechamiento forestal',                     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Compensaciones ambientales',                                'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permiso de emisiones atmosféricas',                         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Registro de generador de residuos peligrosos',              'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plan de gestión integral de residuos peligrosos',           'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
