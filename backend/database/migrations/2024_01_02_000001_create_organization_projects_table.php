<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migracion v1.1 — Reunion cliente 2024-01-06
 *
 * Puntos 1 y 12: El cliente solicito CRUD para "proyectos de la organizacion"
 * como concepto principal, distinto de los "proyectos de monitoreo" (monitorings/fichas PMA).
 *
 * Decisiones de diseno:
 * - organization_projects es un concepto nuevo: los proyectos internos de la empresa cliente
 *   (ej: "Construccion Planta Norte", "Expansion Area 3") que generan obligaciones ambientales.
 * - Se ubica directamente bajo companies, sin depender de programs/projects (son conceptos
 *   paralelos, no jerarquicos).
 * - La tabla monitorings (fichas PMA) se mantiene para trazabilidad regulatoria, pero ya no
 *   es el punto de entrada principal del flujo.
 * - La relacion con obligations se gestiona via tabla pivot (Punto 2).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena del proyecto de organizacion (multi-tenancy)');
            $table->string('name', 200)->comment('Nombre del proyecto de la organizacion');
            $table->string('code', 50)->nullable()->comment('Codigo interno del proyecto');
            $table->text('description')->nullable()->comment('Descripcion del proyecto');
            $table->string('location', 300)->nullable()->comment('Lugar o ubicacion del proyecto');
            $table->foreignId('municipality_id')
                ->nullable()
                ->constrained('municipalities')
                ->nullOnDelete()
                ->comment('Municipio donde se ejecuta el proyecto');
            $table->date('start_date')->nullable()->comment('Fecha de inicio del proyecto');
            $table->date('end_date')->nullable()->comment('Fecha estimada de cierre');
            $table->string('status', 30)->default('activo')
                ->comment('Estado: activo, pausado, cerrado, finalizado');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_org_projects_company_id');
            $table->index('municipality_id', 'idx_org_projects_municipality_id');
            $table->index('status', 'idx_org_projects_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_projects');
    }
};
