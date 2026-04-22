<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worksheets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la ficha (desnormalizado para multi-tenancy)');
            // v1.1: obligation_id es la FK principal en el nuevo flujo.
            // Se declara como unsignedBigInteger sin constrained() porque obligations
            // se crea en migracion posterior (2024_01_02_000002). La FK se agrega via
            // migracion 2024_01_02_000005_add_obligation_fk_to_worksheets.
            $table->unsignedBigInteger('obligation_id')->nullable()
                ->comment('Obligacion ambiental a la que pertenece esta ficha (nuevo flujo principal v1.1) — FK agregada en migracion 2024_01_02_000005');
            // monitoring_id se mantiene como FK opcional para trazabilidad regulatoria
            $table->foreignId('monitoring_id')
                ->nullable()
                ->constrained('monitorings')
                ->nullOnDelete()
                ->comment('Ficha PMA (trazabilidad regulatoria — flujo secundario, conservado para compatibilidad)');
            // Punto 13: El cliente solicito eliminar desplegables en crear obligaciones.
            // monitoring_tool y monitoring_phase pasan a ser texto libre (sin FK a catalogos).
            $table->string('monitoring_tool', 200)->nullable()->comment('Herramienta o instrumento de monitoreo — texto libre (antes FK a monitoring_tools)');
            $table->string('monitoring_phase', 100)->nullable()->comment('Fase del proyecto — texto libre (antes FK a monitoring_phases)');
            $table->string('name', 200)->nullable()->comment('Nombre descriptivo de la ficha de trabajo');
            $table->text('objective')->nullable()->comment('Objetivo de la ficha');
            $table->text('target')->nullable()->comment('Meta a alcanzar');
            $table->text('observations')->nullable()->comment('Observaciones generales');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_worksheets_company_id');
            // idx_worksheets_obligation_id se crea en migracion 2024_01_02_000005 junto con la FK
            $table->index('monitoring_id', 'idx_worksheets_monitoring_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worksheets');
    }
};
