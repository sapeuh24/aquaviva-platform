<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena del programa');
            $table->foreignId('environmental_medium_id')
                ->constrained('environmental_media')
                ->restrictOnDelete()
                ->comment('Medio ambiental del programa');
            $table->string('name', 200)->comment('Nombre del programa ambiental');
            $table->string('code', 50)->comment('Codigo interno del programa');
            $table->text('description')->nullable()->comment('Descripcion y objetivos del programa');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code'], 'uq_programs_company_code');
            $table->index('company_id', 'idx_programs_company_id');
            $table->index('environmental_medium_id', 'idx_programs_environmental_medium_id');
            $table->index('code', 'idx_programs_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
