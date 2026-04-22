<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena del proyecto (desnormalizado para multi-tenancy eficiente)');
            $table->foreignId('program_id')
                ->constrained('programs')
                ->restrictOnDelete()
                ->comment('Programa al que pertenece este proyecto');
            $table->string('name', 200)->comment('Nombre del proyecto');
            $table->string('code', 50)->nullable()->comment('Codigo interno del proyecto');
            $table->text('description')->nullable()->comment('Descripcion del proyecto');
            $table->date('start_date')->nullable()->comment('Fecha de inicio del proyecto');
            $table->date('end_date')->nullable()->comment('Fecha de cierre prevista');
            $table->string('status', 30)->default('activo')->comment('Estado: activo, pausado, cerrado');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_projects_company_id');
            $table->index('program_id', 'idx_projects_program_id');
            $table->index('status', 'idx_projects_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
