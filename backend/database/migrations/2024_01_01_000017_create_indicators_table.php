<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena del indicador (desnormalizado para multi-tenancy)');
            $table->foreignId('worksheet_id')
                ->constrained('worksheets')
                ->restrictOnDelete()
                ->comment('Ficha de trabajo a la que pertenece este indicador');
            $table->foreignId('indicator_frequency_id')
                ->nullable()
                ->constrained('indicator_frequencies')
                ->nullOnDelete()
                ->comment('Frecuencia de medicion del indicador');
            $table->text('name')->comment('Nombre o descripcion del indicador');
            $table->text('objective')->nullable()->comment('Objetivo del indicador');
            $table->text('target')->nullable()->comment('Meta o valor esperado (texto)');
            $table->string('measurement_unit', 100)->nullable()->comment('Unidad de medida: %, m3, ton, etc.');
            $table->decimal('baseline_value', 15, 4)->nullable()->comment('Valor de linea base (numerico)');
            $table->decimal('target_value', 15, 4)->nullable()->comment('Valor meta numerico');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_indicators_company_id');
            $table->index('worksheet_id', 'idx_indicators_worksheet_id');
            $table->index('indicator_frequency_id', 'idx_indicators_indicator_frequency_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
