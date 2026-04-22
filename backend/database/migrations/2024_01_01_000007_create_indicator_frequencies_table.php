<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_frequencies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('name', 100)->unique()->comment('Nombre: Mensual, Bimestral, Trimestral, Semestral, Anual, Puntual');
            $table->unsignedTinyInteger('months_interval')->nullable()->comment('Intervalo en meses (1, 2, 3, 6, 12 — NULL para puntual)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_frequencies');
    }
};
