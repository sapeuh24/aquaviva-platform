<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DocumentTypeSeeder::class,
            EnvironmentalAuthoritySeeder::class,
            DepartmentAndMunicipalitySeeder::class,
            EnvironmentalMediaSeeder::class,
            MonitoringPhaseSeeder::class,
            IndicatorFrequencySeeder::class,
            MonitoringToolSeeder::class,
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
