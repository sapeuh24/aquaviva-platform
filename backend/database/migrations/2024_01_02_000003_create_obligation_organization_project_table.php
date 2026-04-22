<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migracion v1.1 — Reunion cliente 2024-01-06
 *
 * Punto 2: Tabla pivot para la relacion M:N entre obligations y organization_projects.
 * "Una obligacion puede tener muchos proyectos de la organizacion."
 *
 * Nombre de la tabla: obligation_organization_project
 * Convencion Laravel para pivotes: nombres de las dos tablas en singular, orden alfabetico.
 * obligation < organization_project → obligation_organization_project
 *
 * Nota sobre CASCADE: Si se elimina (soft delete) una obligacion o un proyecto, la asociacion
 * pierde sentido pero usamos RESTRICT en la FK hacia el ID real para forzar desvinculacion
 * explicita antes de eliminar. Los soft deletes en las tablas padre hacen que el registro
 * siga existiendo fisicamente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obligation_organization_project', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('obligation_id')
                ->constrained('obligations')
                ->cascadeOnDelete()
                ->comment('Obligacion ambiental');
            $table->foreignId('organization_project_id')
                ->constrained('organization_projects')
                ->cascadeOnDelete()
                ->comment('Proyecto de la organizacion');
            $table->text('notes')->nullable()
                ->comment('Notas sobre la relacion especifica entre esta obligacion y este proyecto');
            $table->timestamps();

            // Garantizar que no haya duplicados en la relacion
            $table->unique(
                ['obligation_id', 'organization_project_id'],
                'uq_obligation_org_project'
            );
            $table->index('organization_project_id', 'idx_oblig_org_proj_project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obligation_organization_project');
    }
};
