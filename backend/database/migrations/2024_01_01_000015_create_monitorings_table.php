<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la ficha (desnormalizado para multi-tenancy)');
            $table->foreignId('project_id')
                ->constrained('projects')
                ->restrictOnDelete()
                ->comment('Proyecto al que pertenece la ficha PMA');
            $table->foreignId('municipality_id')
                ->nullable()
                ->constrained('municipalities')
                ->nullOnDelete()
                ->comment('Municipio donde se realiza el monitoreo');
            $table->foreignId('environmental_authority_id')
                ->nullable()
                ->constrained('environmental_authorities')
                ->nullOnDelete()
                ->comment('Autoridad ambiental competente');
            $table->string('name', 200)->comment('Nombre de la ficha PMA');
            $table->text('specification')->nullable()->comment('Especificaciones tecnicas del monitoreo');
            $table->string('resolution_number', 100)->nullable()->comment('Numero de resolucion ambiental');
            $table->date('resolution_date')->nullable()->comment('Fecha de la resolucion');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_monitorings_company_id');
            $table->index('project_id', 'idx_monitorings_project_id');
            $table->index('municipality_id', 'idx_monitorings_municipality_id');
            $table->index('environmental_authority_id', 'idx_monitorings_environmental_authority_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
