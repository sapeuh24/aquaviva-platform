<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();
            $table->string('name', 150)->comment('Nombre del municipio');
            $table->string('dane_code', 5)->unique()->comment('Codigo DANE oficial del municipio (5 digitos: 2 dept + 3 municipio, ej: 05001=Medellin)');
            $table->string('dane_department_code', 2)->nullable()->comment('Codigo DANE del departamento (redundante para busquedas rapidas sin JOIN)');
            $table->timestamps();

            $table->index('department_id', 'idx_municipalities_department_id');
            $table->index('name', 'idx_municipalities_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
