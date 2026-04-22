<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la actividad (desnormalizado para multi-tenancy)');
            $table->foreignId('indicator_id')
                ->constrained('indicators')
                ->restrictOnDelete()
                ->comment('Indicador al que pertenece esta actividad');
            $table->text('name')->comment('Descripcion de la actividad a ejecutar');
            $table->date('scheduled_date')->nullable()->comment('Fecha programada de ejecucion');
            $table->date('executed_date')->nullable()->comment('Fecha real de ejecucion');
            $table->string('status', 30)->default('pendiente')
                ->comment('Estado: pendiente, en_ejecucion, cumplida, no_cumplida, parcial');
            $table->text('observations')->nullable()->comment('Observaciones de seguimiento');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_activities_company_id');
            $table->index('indicator_id', 'idx_activities_indicator_id');
            $table->index('status', 'idx_activities_status');
            $table->index('scheduled_date', 'idx_activities_scheduled_date');
            $table->index('executed_date', 'idx_activities_executed_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
