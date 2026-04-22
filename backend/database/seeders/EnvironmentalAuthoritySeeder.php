<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvironmentalAuthoritySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('environmental_authorities')->insertOrIgnore([
            ['name' => 'Corporación Autónoma Regional del Alto Magdalena',                                        'acronym' => 'CAM',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Cundinamarca',                                          'acronym' => 'CAR',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Risaralda',                                             'acronym' => 'CARDER',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Canal Del Dique',                                      'acronym' => 'CARDIQUE',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Sucre',                                                 'acronym' => 'CARSUCRE',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Santander',                                             'acronym' => 'CAS',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional para la Defensa de la Meseta de Bucaramanga',              'acronym' => 'CDMB',          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Centro de Antioquia',                                  'acronym' => 'CORANTIOQUIA',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de las Cuencas de los Ríos Negro y Nare',                  'acronym' => 'CORNARE',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Magdalena',                                            'acronym' => 'CORPAMAG',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Caldas',                                                'acronym' => 'CORPOCALDAS',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Cesar',                                                'acronym' => 'CORPOCESAR',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Chivor',                                                'acronym' => 'CORPOCHIVOR',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de La Guajira',                                            'acronym' => 'CORPOGUAJIRA',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Guavio',                                               'acronym' => 'CORPOGUAVIO',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de Nariño',                                                'acronym' => 'CORPONARIÑO',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de la Frontera Nororiental',                               'acronym' => 'CORPONOR',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de la Orinoquia',                                          'acronym' => 'CORPORINOQUIA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Tolima',                                               'acronym' => 'CORPOTOLIMA',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Atlántico',                                            'acronym' => 'CRA',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Cauca',                                                'acronym' => 'CRC',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Quindío',                                              'acronym' => 'CRQ',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Sur de Bolívar',                                       'acronym' => 'CSB',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional del Valle del Cauca',                                      'acronym' => 'CVC',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional de los Valles del Sinú y del San Jorge',                   'acronym' => 'CVS',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible del Norte y el Oriente Amazónico',             'acronym' => 'CDA',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación Autónoma Regional para el Desarrollo Sostenible del Chocó',                  'acronym' => 'CODECHOCO',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible del Archipiélago de San Andrés',               'acronym' => 'CORALINA',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible del Área de Manejo Especial de La Macarena',   'acronym' => 'CORMACARENA',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible del Sur de la Amazonia',                       'acronym' => 'CORPOAMAZONIA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible de La Mojana y El San Jorge',                  'acronym' => 'CORPOMOJANA',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Corporación para el Desarrollo Sostenible del Urabá',                                    'acronym' => 'CORPOURABA',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Secretaría Distrital de Ambiente de Bogotá',                                             'acronym' => 'SDA',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Área Metropolitana del Valle de Aburrá',                                                 'acronym' => 'AMVA',          'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Departamento Administrativo de Gestión del Medio Ambiente de Cali',                      'acronym' => 'DAGMA',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Departamento Técnico Administrativo del Medio Ambiente de Barranquilla',                 'acronym' => 'DAMAB',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Departamento Administrativo Distrital del Medio Ambiente de Santa Marta',                'acronym' => 'DADMA',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Establecimiento Público Ambiental de Cartagena',                                         'acronym' => 'EPA',           'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Autoridad Nacional de Licencias Ambientales',                                            'acronym' => 'ANLA',          'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
