<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Configuracion de Pest para Aquaviva Platform
|--------------------------------------------------------------------------
|
| Feature tests: usan RefreshDatabase para limpiar la DB SQLite en memoria
| entre cada test.
|
| Unit tests: solo necesitan el TestCase base de Laravel.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class, RefreshDatabase::class)->in('Unit');
