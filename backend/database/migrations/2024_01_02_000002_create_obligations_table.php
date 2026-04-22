<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migracion v1.1 — Reunion cliente 2024-01-06
 *
 * Punto 2: "Una obligacion puede tener muchos proyectos de la organizacion"
 * Punto 13: "En el menu de crear obligaciones eliminar opciones desplegables
 *            y dejar ingresar texto/numeros"
 *
 * Decisiones de diseno:
 * - "Obligaciones ambientales" es el concepto central del dominio ambiental colombiano:
 *   son los requisitos especificos derivados de una Resolucion de Licencia Ambiental,
 *   Plan de Manejo Ambiental (PMA), permiso de vertimientos, concesion de aguas, etc.
 *   Ejemplo: "Implementar sistema de tratamiento de aguas residuales antes del mes 6".
 *
 * - En el sistema anterior, este concepto estaba difuso entre `worksheet`, `enviorenmental_monitoring`
 *   y la referencia `id_obligation` en alerts (sin FK real). En el nuevo sistema se
 *   materializa como tabla propia.
 *
 * - La relacion con organization_projects es M:N (una obligacion aplica a multiples proyectos;
 *   un proyecto puede tener multiples obligaciones). Se gestiona via pivot
 *   `obligation_organization_project`.
 *
 * - Se mantiene la relacion con `monitorings` (ficha PMA) como FK opcional: una obligacion
 *   puede estar asociada a una ficha PMA para trazabilidad regulatoria.
 *
 * - Todos los campos que antes eran FK a catalogos (herramienta, fase, etc.) son ahora
 *   texto libre segun Punto 13 del cliente.
 *
 * - La jerarquia resultante para el flujo principal es:
 *   companies → organization_projects ←M:N→ obligations → worksheets → indicators → activities → evidences
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obligations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la obligacion (multi-tenancy)');
            // Relacion opcional con ficha PMA para trazabilidad regulatoria
            $table->foreignId('monitoring_id')
                ->nullable()
                ->constrained('monitorings')
                ->nullOnDelete()
                ->comment('Ficha PMA de la que deriva esta obligacion (opcional, para trazabilidad)');
            // FK a autoridad ambiental que emitio el instrumento que genera esta obligacion
            $table->foreignId('environmental_authority_id')
                ->nullable()
                ->constrained('environmental_authorities')
                ->nullOnDelete()
                ->comment('Autoridad ambiental que emitio la obligacion');

            // Identificacion del instrumento legal
            $table->string('resolution_number', 100)->nullable()
                ->comment('Numero de resolucion, permiso, licencia o acto administrativo');
            $table->date('resolution_date')->nullable()
                ->comment('Fecha de emision del instrumento legal');
            $table->string('instrument_type', 150)->nullable()
                ->comment('Tipo de instrumento: Licencia Ambiental, Permiso de Vertimientos, Concesion Aguas, PMA, etc. — texto libre');

            // Descripcion de la obligacion
            $table->string('name', 300)->comment('Nombre o titulo corto de la obligacion ambiental');
            $table->text('description')->nullable()->comment('Descripcion completa de la obligacion');
            $table->text('legal_basis')->nullable()->comment('Fundamento legal o articulo del instrumento');

            // Clasificacion — Punto 13: texto libre, sin desplegables
            $table->string('environmental_medium', 150)->nullable()
                ->comment('Medio ambiental — texto libre (antes FK a environmental_media)');
            $table->string('obligation_type', 150)->nullable()
                ->comment('Tipo de obligacion: medida de manejo, monitoreo, compensacion, etc. — texto libre');

            // Control de cumplimiento
            $table->date('compliance_deadline')->nullable()
                ->comment('Fecha limite de cumplimiento');
            $table->string('compliance_frequency', 100)->nullable()
                ->comment('Frecuencia de cumplimiento: mensual, trimestral, unica vez, etc. — texto libre');
            $table->string('status', 30)->default('vigente')
                ->comment('Estado: vigente, cumplida, vencida, suspendida');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_obligations_company_id');
            $table->index('monitoring_id', 'idx_obligations_monitoring_id');
            $table->index('environmental_authority_id', 'idx_obligations_authority_id');
            $table->index('status', 'idx_obligations_status');
            $table->index('compliance_deadline', 'idx_obligations_compliance_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obligations');
    }
};
