<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_controls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena del registro (desnormalizado para multi-tenancy)');
            $table->foreignId('indicator_id')
                ->constrained('indicators')
                ->restrictOnDelete()
                ->comment('Indicador evaluado en este control');
            $table->unsignedBigInteger('measured_by')
                ->nullable()
                ->comment('Usuario que registro el control de cumplimiento');
            $table->foreign('measured_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->date('period_start')->comment('Inicio del periodo de medicion');
            $table->date('period_end')->comment('Fin del periodo de medicion');
            $table->string('parameter_name', 200)->nullable()->comment('Nombre del parametro evaluado');
            $table->string('parameter_value', 300)->nullable()->comment('Valor obtenido del parametro');
            $table->string('feature_name', 200)->nullable()->comment('Nombre de la caracteristica evaluada');
            $table->string('feature_value', 300)->nullable()->comment('Valor obtenido de la caracteristica');
            $table->decimal('compliance_percentage', 5, 2)->nullable()
                ->comment('Porcentaje de cumplimiento: 0.00 a 100.00');
            $table->string('compliance_status', 30)
                ->comment('Estado de cumplimiento: cumplido, parcial, no_cumplido');
            $table->text('notes')->nullable()->comment('Notas adicionales del evaluador');
            $table->timestamps();

            $table->index('company_id', 'idx_indicator_controls_company_id');
            $table->index('indicator_id', 'idx_indicator_controls_indicator_id');
            $table->index('measured_by', 'idx_indicator_controls_measured_by');
            $table->index('period_start', 'idx_indicator_controls_period_start');
            $table->index('compliance_status', 'idx_indicator_controls_compliance_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_controls');
    }
};
