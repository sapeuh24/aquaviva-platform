<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environmental_authorities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('name', 200)->comment('Nombre completo de la autoridad ambiental');
            $table->string('acronym', 30)->unique()->comment('Sigla: ANLA, CAR, CVC, CORPOCALDAS, etc.');
            $table->string('jurisdiction', 200)->nullable()->comment('Descripcion de la jurisdiccion geografica');
            $table->timestamps();

            $table->index('name', 'idx_environmental_authorities_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environmental_authorities');
    }
};
