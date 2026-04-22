<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.delete',
            'companies.list',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.list',

            'programs.view',
            'programs.create',
            'programs.update',
            'programs.delete',
            'programs.list',

            'organization_projects.view',
            'organization_projects.create',
            'organization_projects.update',
            'organization_projects.delete',
            'organization_projects.list',

            'obligations.view',
            'obligations.create',
            'obligations.update',
            'obligations.delete',
            'obligations.list',

            'worksheets.view',
            'worksheets.create',
            'worksheets.update',
            'worksheets.delete',
            'worksheets.list',

            'indicators.view',
            'indicators.create',
            'indicators.update',
            'indicators.delete',
            'indicators.list',

            'activities.view',
            'activities.create',
            'activities.update',
            'activities.delete',
            'activities.list',

            'evidences.view',
            'evidences.create',
            'evidences.delete',
            'evidences.list',

            'alerts.view',
            'alerts.list',
            'alerts.dismiss',

            'reports.view',
            'reports.export',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
        $coordinator = Role::firstOrCreate(['name' => 'coordinator','guard_name' => 'web']);
        $analyst    = Role::firstOrCreate(['name' => 'analyst',     'guard_name' => 'web']);
        $viewer     = Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

        $superAdmin->syncPermissions($permissions);

        $adminPermissions = array_filter($permissions, function (string $p): bool {
            return $p !== 'companies.create' && $p !== 'companies.delete';
        });
        $admin->syncPermissions(array_values($adminPermissions));

        $coordinator->syncPermissions([
            'companies.view',
            'companies.list',
            'users.view',
            'users.list',
            'programs.view',
            'programs.create',
            'programs.update',
            'programs.list',
            'organization_projects.view',
            'organization_projects.create',
            'organization_projects.update',
            'organization_projects.list',
            'obligations.view',
            'obligations.create',
            'obligations.update',
            'obligations.list',
            'worksheets.view',
            'worksheets.create',
            'worksheets.update',
            'worksheets.list',
            'indicators.view',
            'indicators.create',
            'indicators.update',
            'indicators.list',
            'activities.view',
            'activities.create',
            'activities.update',
            'activities.list',
            'evidences.view',
            'evidences.create',
            'evidences.list',
            'alerts.view',
            'alerts.list',
        ]);

        $analyst->syncPermissions([
            'companies.view',
            'companies.list',
            'users.view',
            'users.list',
            'programs.view',
            'programs.list',
            'organization_projects.view',
            'organization_projects.list',
            'obligations.view',
            'obligations.list',
            'worksheets.view',
            'worksheets.list',
            'indicators.view',
            'indicators.list',
            'activities.view',
            'activities.create',
            'activities.list',
            'evidences.view',
            'evidences.create',
            'evidences.list',
            'alerts.view',
            'alerts.list',
            'reports.view',
        ]);

        $viewerPermissions = array_filter($permissions, function (string $p): bool {
            [$action] = explode('.', $p);
            $verb = substr($p, strpos($p, '.') + 1);
            return $verb === 'view' || $verb === 'list';
        });
        $viewer->syncPermissions(array_values($viewerPermissions));
    }
}
