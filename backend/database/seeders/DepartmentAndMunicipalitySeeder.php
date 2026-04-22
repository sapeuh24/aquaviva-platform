<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentAndMunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $departments = [
            ['name' => 'Antioquia',                        'dane_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Atlántico',                        'dane_code' => '08', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bogotá D.C.',                      'dane_code' => '11', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bolívar',                          'dane_code' => '13', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Boyacá',                           'dane_code' => '15', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caldas',                           'dane_code' => '17', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caquetá',                          'dane_code' => '18', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cauca',                            'dane_code' => '19', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cesar',                            'dane_code' => '20', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Córdoba',                          'dane_code' => '23', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cundinamarca',                     'dane_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chocó',                            'dane_code' => '27', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Huila',                            'dane_code' => '41', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'La Guajira',                       'dane_code' => '44', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Magdalena',                        'dane_code' => '47', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Meta',                             'dane_code' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nariño',                           'dane_code' => '52', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Norte de Santander',               'dane_code' => '54', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quindío',                          'dane_code' => '63', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Risaralda',                        'dane_code' => '66', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Santander',                        'dane_code' => '68', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sucre',                            'dane_code' => '70', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tolima',                           'dane_code' => '73', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Valle del Cauca',                  'dane_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arauca',                           'dane_code' => '81', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Casanare',                         'dane_code' => '85', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Putumayo',                         'dane_code' => '86', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Archipiélago de San Andrés',       'dane_code' => '88', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Amazonas',                         'dane_code' => '91', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Guainía',                          'dane_code' => '94', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Guaviare',                         'dane_code' => '95', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vaupés',                           'dane_code' => '97', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vichada',                          'dane_code' => '99', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('departments')->insertOrIgnore($departments);

        $deptIdByCode = DB::table('departments')
            ->pluck('id', 'dane_code')
            ->toArray();

        $municipalities = [
            // Antioquia (05)
            ['department_id' => $deptIdByCode['05'], 'name' => 'Medellín',        'dane_code' => '05001', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Bello',           'dane_code' => '05088', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Itagüí',          'dane_code' => '05360', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Envigado',        'dane_code' => '05266', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Apartadó',        'dane_code' => '05045', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Rionegro',        'dane_code' => '05615', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['05'], 'name' => 'Turbo',           'dane_code' => '05837', 'dane_department_code' => '05', 'created_at' => $now, 'updated_at' => $now],

            // Atlántico (08)
            ['department_id' => $deptIdByCode['08'], 'name' => 'Barranquilla',    'dane_code' => '08001', 'dane_department_code' => '08', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['08'], 'name' => 'Soledad',         'dane_code' => '08758', 'dane_department_code' => '08', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['08'], 'name' => 'Malambo',         'dane_code' => '08433', 'dane_department_code' => '08', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['08'], 'name' => 'Sabanalarga',     'dane_code' => '08638', 'dane_department_code' => '08', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['08'], 'name' => 'Baranoa',         'dane_code' => '08078', 'dane_department_code' => '08', 'created_at' => $now, 'updated_at' => $now],

            // Bogotá D.C. (11)
            ['department_id' => $deptIdByCode['11'], 'name' => 'Bogotá D.C.',     'dane_code' => '11001', 'dane_department_code' => '11', 'created_at' => $now, 'updated_at' => $now],

            // Bolívar (13)
            ['department_id' => $deptIdByCode['13'], 'name' => 'Cartagena',             'dane_code' => '13001', 'dane_department_code' => '13', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['13'], 'name' => 'Magangué',              'dane_code' => '13430', 'dane_department_code' => '13', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['13'], 'name' => 'El Carmen de Bolívar',  'dane_code' => '13244', 'dane_department_code' => '13', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['13'], 'name' => 'Mompox',                'dane_code' => '13468', 'dane_department_code' => '13', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['13'], 'name' => 'Turbaco',               'dane_code' => '13836', 'dane_department_code' => '13', 'created_at' => $now, 'updated_at' => $now],

            // Boyacá (15)
            ['department_id' => $deptIdByCode['15'], 'name' => 'Tunja',           'dane_code' => '15001', 'dane_department_code' => '15', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['15'], 'name' => 'Duitama',         'dane_code' => '15238', 'dane_department_code' => '15', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['15'], 'name' => 'Sogamoso',        'dane_code' => '15759', 'dane_department_code' => '15', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['15'], 'name' => 'Chiquinquirá',    'dane_code' => '15176', 'dane_department_code' => '15', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['15'], 'name' => 'Paipa',           'dane_code' => '15516', 'dane_department_code' => '15', 'created_at' => $now, 'updated_at' => $now],

            // Caldas (17)
            ['department_id' => $deptIdByCode['17'], 'name' => 'Manizales',       'dane_code' => '17001', 'dane_department_code' => '17', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['17'], 'name' => 'Villamaría',      'dane_code' => '17877', 'dane_department_code' => '17', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['17'], 'name' => 'Chinchiná',       'dane_code' => '17174', 'dane_department_code' => '17', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['17'], 'name' => 'Riosucio',        'dane_code' => '17614', 'dane_department_code' => '17', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['17'], 'name' => 'La Dorada',       'dane_code' => '17380', 'dane_department_code' => '17', 'created_at' => $now, 'updated_at' => $now],

            // Caquetá (18)
            ['department_id' => $deptIdByCode['18'], 'name' => 'Florencia',                  'dane_code' => '18001', 'dane_department_code' => '18', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['18'], 'name' => 'San Vicente del Caguán',     'dane_code' => '18753', 'dane_department_code' => '18', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['18'], 'name' => 'Puerto Rico',                'dane_code' => '18592', 'dane_department_code' => '18', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['18'], 'name' => 'Belén de los Andaquíes',     'dane_code' => '18094', 'dane_department_code' => '18', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['18'], 'name' => 'La Montañita',               'dane_code' => '18410', 'dane_department_code' => '18', 'created_at' => $now, 'updated_at' => $now],

            // Cauca (19)
            ['department_id' => $deptIdByCode['19'], 'name' => 'Popayán',                   'dane_code' => '19001', 'dane_department_code' => '19', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['19'], 'name' => 'Santander de Quilichao',    'dane_code' => '19698', 'dane_department_code' => '19', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['19'], 'name' => 'Puerto Tejada',             'dane_code' => '19573', 'dane_department_code' => '19', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['19'], 'name' => 'Patía',                     'dane_code' => '19532', 'dane_department_code' => '19', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['19'], 'name' => 'Miranda',                   'dane_code' => '19473', 'dane_department_code' => '19', 'created_at' => $now, 'updated_at' => $now],

            // Cesar (20)
            ['department_id' => $deptIdByCode['20'], 'name' => 'Valledupar',      'dane_code' => '20001', 'dane_department_code' => '20', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['20'], 'name' => 'Aguachica',       'dane_code' => '20011', 'dane_department_code' => '20', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['20'], 'name' => 'Bosconia',        'dane_code' => '20175', 'dane_department_code' => '20', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['20'], 'name' => 'Codazzi',         'dane_code' => '20228', 'dane_department_code' => '20', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['20'], 'name' => 'La Paz',          'dane_code' => '20621', 'dane_department_code' => '20', 'created_at' => $now, 'updated_at' => $now],

            // Córdoba (23)
            ['department_id' => $deptIdByCode['23'], 'name' => 'Montería',        'dane_code' => '23001', 'dane_department_code' => '23', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['23'], 'name' => 'Lorica',          'dane_code' => '23417', 'dane_department_code' => '23', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['23'], 'name' => 'Sahagún',         'dane_code' => '23660', 'dane_department_code' => '23', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['23'], 'name' => 'Cereté',          'dane_code' => '23162', 'dane_department_code' => '23', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['23'], 'name' => 'Planeta Rica',    'dane_code' => '23555', 'dane_department_code' => '23', 'created_at' => $now, 'updated_at' => $now],

            // Cundinamarca (25)
            ['department_id' => $deptIdByCode['25'], 'name' => 'Soacha',          'dane_code' => '25754', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Facatativá',      'dane_code' => '25269', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Zipaquirá',       'dane_code' => '25899', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Fusagasugá',      'dane_code' => '25307', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Chía',            'dane_code' => '25175', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Mosquera',        'dane_code' => '25473', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['25'], 'name' => 'Madrid',          'dane_code' => '25430', 'dane_department_code' => '25', 'created_at' => $now, 'updated_at' => $now],

            // Chocó (27)
            ['department_id' => $deptIdByCode['27'], 'name' => 'Quibdó',          'dane_code' => '27001', 'dane_department_code' => '27', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['27'], 'name' => 'Istmina',         'dane_code' => '27361', 'dane_department_code' => '27', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['27'], 'name' => 'Tadó',            'dane_code' => '27787', 'dane_department_code' => '27', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['27'], 'name' => 'Condoto',         'dane_code' => '27205', 'dane_department_code' => '27', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['27'], 'name' => 'Bagadó',          'dane_code' => '27073', 'dane_department_code' => '27', 'created_at' => $now, 'updated_at' => $now],

            // Huila (41)
            ['department_id' => $deptIdByCode['41'], 'name' => 'Neiva',           'dane_code' => '41001', 'dane_department_code' => '41', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['41'], 'name' => 'Pitalito',        'dane_code' => '41551', 'dane_department_code' => '41', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['41'], 'name' => 'Garzón',          'dane_code' => '41298', 'dane_department_code' => '41', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['41'], 'name' => 'La Plata',        'dane_code' => '41396', 'dane_department_code' => '41', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['41'], 'name' => 'Campoalegre',     'dane_code' => '41132', 'dane_department_code' => '41', 'created_at' => $now, 'updated_at' => $now],

            // La Guajira (44)
            ['department_id' => $deptIdByCode['44'], 'name' => 'Riohacha',              'dane_code' => '44001', 'dane_department_code' => '44', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['44'], 'name' => 'Maicao',                'dane_code' => '44430', 'dane_department_code' => '44', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['44'], 'name' => 'Uribia',                'dane_code' => '44847', 'dane_department_code' => '44', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['44'], 'name' => 'Manaure',               'dane_code' => '44560', 'dane_department_code' => '44', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['44'], 'name' => 'San Juan del Cesar',    'dane_code' => '44650', 'dane_department_code' => '44', 'created_at' => $now, 'updated_at' => $now],

            // Magdalena (47)
            ['department_id' => $deptIdByCode['47'], 'name' => 'Santa Marta',     'dane_code' => '47001', 'dane_department_code' => '47', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['47'], 'name' => 'Ciénaga',         'dane_code' => '47189', 'dane_department_code' => '47', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['47'], 'name' => 'Fundación',       'dane_code' => '47288', 'dane_department_code' => '47', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['47'], 'name' => 'El Banco',        'dane_code' => '47245', 'dane_department_code' => '47', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['47'], 'name' => 'Plato',           'dane_code' => '47541', 'dane_department_code' => '47', 'created_at' => $now, 'updated_at' => $now],

            // Meta (50)
            ['department_id' => $deptIdByCode['50'], 'name' => 'Villavicencio',   'dane_code' => '50001', 'dane_department_code' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['50'], 'name' => 'Acacías',         'dane_code' => '50006', 'dane_department_code' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['50'], 'name' => 'Granada',         'dane_code' => '50313', 'dane_department_code' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['50'], 'name' => 'Puerto López',    'dane_code' => '50577', 'dane_department_code' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['50'], 'name' => 'San Martín',      'dane_code' => '50689', 'dane_department_code' => '50', 'created_at' => $now, 'updated_at' => $now],

            // Nariño (52)
            ['department_id' => $deptIdByCode['52'], 'name' => 'Pasto',           'dane_code' => '52001', 'dane_department_code' => '52', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['52'], 'name' => 'Tumaco',          'dane_code' => '52835', 'dane_department_code' => '52', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['52'], 'name' => 'Ipiales',         'dane_code' => '52356', 'dane_department_code' => '52', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['52'], 'name' => 'Túquerres',       'dane_code' => '52838', 'dane_department_code' => '52', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['52'], 'name' => 'La Unión',        'dane_code' => '52399', 'dane_department_code' => '52', 'created_at' => $now, 'updated_at' => $now],

            // Norte de Santander (54)
            ['department_id' => $deptIdByCode['54'], 'name' => 'Cúcuta',          'dane_code' => '54001', 'dane_department_code' => '54', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['54'], 'name' => 'Ocaña',           'dane_code' => '54498', 'dane_department_code' => '54', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['54'], 'name' => 'Pamplona',        'dane_code' => '54518', 'dane_department_code' => '54', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['54'], 'name' => 'Villa del Rosario','dane_code' => '54874', 'dane_department_code' => '54', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['54'], 'name' => 'Los Patios',      'dane_code' => '54405', 'dane_department_code' => '54', 'created_at' => $now, 'updated_at' => $now],

            // Quindío (63)
            ['department_id' => $deptIdByCode['63'], 'name' => 'Armenia',         'dane_code' => '63001', 'dane_department_code' => '63', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['63'], 'name' => 'Calarcá',         'dane_code' => '63130', 'dane_department_code' => '63', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['63'], 'name' => 'Montenegro',      'dane_code' => '63470', 'dane_department_code' => '63', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['63'], 'name' => 'Quimbaya',        'dane_code' => '63594', 'dane_department_code' => '63', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['63'], 'name' => 'La Tebaida',      'dane_code' => '63401', 'dane_department_code' => '63', 'created_at' => $now, 'updated_at' => $now],

            // Risaralda (66)
            ['department_id' => $deptIdByCode['66'], 'name' => 'Pereira',               'dane_code' => '66001', 'dane_department_code' => '66', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['66'], 'name' => 'Dosquebradas',          'dane_code' => '66170', 'dane_department_code' => '66', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['66'], 'name' => 'Santa Rosa de Cabal',   'dane_code' => '66682', 'dane_department_code' => '66', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['66'], 'name' => 'La Virginia',           'dane_code' => '66400', 'dane_department_code' => '66', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['66'], 'name' => 'Quinchía',              'dane_code' => '66594', 'dane_department_code' => '66', 'created_at' => $now, 'updated_at' => $now],

            // Santander (68)
            ['department_id' => $deptIdByCode['68'], 'name' => 'Bucaramanga',    'dane_code' => '68001', 'dane_department_code' => '68', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['68'], 'name' => 'Floridablanca',  'dane_code' => '68276', 'dane_department_code' => '68', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['68'], 'name' => 'Girón',          'dane_code' => '68307', 'dane_department_code' => '68', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['68'], 'name' => 'Piedecuesta',    'dane_code' => '68547', 'dane_department_code' => '68', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['68'], 'name' => 'Barrancabermeja','dane_code' => '68081', 'dane_department_code' => '68', 'created_at' => $now, 'updated_at' => $now],

            // Sucre (70)
            ['department_id' => $deptIdByCode['70'], 'name' => 'Sincelejo',      'dane_code' => '70001', 'dane_department_code' => '70', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['70'], 'name' => 'Corozal',        'dane_code' => '70215', 'dane_department_code' => '70', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['70'], 'name' => 'San Marcos',     'dane_code' => '70670', 'dane_department_code' => '70', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['70'], 'name' => 'Tolú',           'dane_code' => '70820', 'dane_department_code' => '70', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['70'], 'name' => 'Morroa',         'dane_code' => '70473', 'dane_department_code' => '70', 'created_at' => $now, 'updated_at' => $now],

            // Tolima (73)
            ['department_id' => $deptIdByCode['73'], 'name' => 'Ibagué',         'dane_code' => '73001', 'dane_department_code' => '73', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['73'], 'name' => 'Espinal',        'dane_code' => '73268', 'dane_department_code' => '73', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['73'], 'name' => 'Melgar',         'dane_code' => '73449', 'dane_department_code' => '73', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['73'], 'name' => 'Honda',          'dane_code' => '73349', 'dane_department_code' => '73', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['73'], 'name' => 'Líbano',         'dane_code' => '73408', 'dane_department_code' => '73', 'created_at' => $now, 'updated_at' => $now],

            // Valle del Cauca (76)
            ['department_id' => $deptIdByCode['76'], 'name' => 'Cali',           'dane_code' => '76001', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Buenaventura',   'dane_code' => '76109', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Palmira',        'dane_code' => '76520', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Tuluá',          'dane_code' => '76834', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Buga',           'dane_code' => '76111', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Cartago',        'dane_code' => '76147', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['76'], 'name' => 'Jamundí',        'dane_code' => '76364', 'dane_department_code' => '76', 'created_at' => $now, 'updated_at' => $now],

            // Arauca (81)
            ['department_id' => $deptIdByCode['81'], 'name' => 'Arauca',         'dane_code' => '81001', 'dane_department_code' => '81', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['81'], 'name' => 'Saravena',       'dane_code' => '81736', 'dane_department_code' => '81', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['81'], 'name' => 'Tame',           'dane_code' => '81794', 'dane_department_code' => '81', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['81'], 'name' => 'Arauquita',      'dane_code' => '81065', 'dane_department_code' => '81', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['81'], 'name' => 'Fortul',         'dane_code' => '81300', 'dane_department_code' => '81', 'created_at' => $now, 'updated_at' => $now],

            // Casanare (85)
            ['department_id' => $deptIdByCode['85'], 'name' => 'Yopal',          'dane_code' => '85001', 'dane_department_code' => '85', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['85'], 'name' => 'Aguazul',        'dane_code' => '85010', 'dane_department_code' => '85', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['85'], 'name' => 'Villanueva',     'dane_code' => '85440', 'dane_department_code' => '85', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['85'], 'name' => 'Paz de Ariporo', 'dane_code' => '85250', 'dane_department_code' => '85', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['85'], 'name' => 'Tauramena',      'dane_code' => '85400', 'dane_department_code' => '85', 'created_at' => $now, 'updated_at' => $now],

            // Putumayo (86)
            ['department_id' => $deptIdByCode['86'], 'name' => 'Mocoa',          'dane_code' => '86001', 'dane_department_code' => '86', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['86'], 'name' => 'Puerto Asís',    'dane_code' => '86568', 'dane_department_code' => '86', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['86'], 'name' => 'Orito',          'dane_code' => '86320', 'dane_department_code' => '86', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['86'], 'name' => 'Sibundoy',       'dane_code' => '86749', 'dane_department_code' => '86', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['86'], 'name' => 'Valle del Guamuez', 'dane_code' => '86865', 'dane_department_code' => '86', 'created_at' => $now, 'updated_at' => $now],

            // Archipiélago de San Andrés (88)
            ['department_id' => $deptIdByCode['88'], 'name' => 'San Andrés',     'dane_code' => '88001', 'dane_department_code' => '88', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['88'], 'name' => 'Providencia',    'dane_code' => '88564', 'dane_department_code' => '88', 'created_at' => $now, 'updated_at' => $now],

            // Amazonas (91)
            ['department_id' => $deptIdByCode['91'], 'name' => 'Leticia',        'dane_code' => '91001', 'dane_department_code' => '91', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['91'], 'name' => 'Puerto Nariño',  'dane_code' => '91540', 'dane_department_code' => '91', 'created_at' => $now, 'updated_at' => $now],

            // Guainía (94)
            ['department_id' => $deptIdByCode['94'], 'name' => 'Inírida',        'dane_code' => '94001', 'dane_department_code' => '94', 'created_at' => $now, 'updated_at' => $now],

            // Guaviare (95)
            ['department_id' => $deptIdByCode['95'], 'name' => 'San José del Guaviare', 'dane_code' => '95001', 'dane_department_code' => '95', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['95'], 'name' => 'El Retorno',             'dane_code' => '95025', 'dane_department_code' => '95', 'created_at' => $now, 'updated_at' => $now],

            // Vaupés (97)
            ['department_id' => $deptIdByCode['97'], 'name' => 'Mitú',           'dane_code' => '97001', 'dane_department_code' => '97', 'created_at' => $now, 'updated_at' => $now],

            // Vichada (99)
            ['department_id' => $deptIdByCode['99'], 'name' => 'Puerto Carreño', 'dane_code' => '99001', 'dane_department_code' => '99', 'created_at' => $now, 'updated_at' => $now],
            ['department_id' => $deptIdByCode['99'], 'name' => 'La Primavera',   'dane_code' => '99524', 'dane_department_code' => '99', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('municipalities')->insertOrIgnore($municipalities);
    }
}
