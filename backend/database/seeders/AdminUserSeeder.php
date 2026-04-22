<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('companies')->insertOrIgnore([
            [
                'name'             => 'Aquaviva SAS',
                'nit'              => '900000001-1',
                'ciiu_code'        => '7220',
                'ciiu_description' => 'Investigación y desarrollo experimental en el campo de las ciencias naturales y la ingeniería',
                'is_active'        => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        $companyId = DB::table('companies')->where('nit', '900000001-1')->value('id');

        $documentTypeId = DB::table('document_types')->where('code', 'CC')->value('id');

        DB::table('users')->insertOrIgnore([
            [
                'company_id'       => $companyId,
                'document_type_id' => $documentTypeId,
                'document_number'  => '000000001',
                'first_name'       => 'Super',
                'last_name'        => 'Admin',
                'email'            => 'superadmin@aquaviva.com.co',
                'password'         => Hash::make('Aquaviva2024*'),
                'is_active'        => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        $user = \App\Models\User::where('email', 'superadmin@aquaviva.com.co')->first();

        if ($user && ! $user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}
