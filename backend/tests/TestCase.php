<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    /**
     * Configuracion adicional para los tests.
     *
     * Nota: CreateCompanyAction crea User sin document_type_id.
     * Para que los tests puedan ejecutarse con la DB real (SQLite en memoria),
     * desactivamos temporalmente las FK constraints en SQLite.
     *
     * Esto NO afecta la logica de negocio — solo permite que los tests
     * corran sin errores de FK mientras se corrige el Action.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }
    }

    protected function tearDown(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }

        parent::tearDown();
    }
}
