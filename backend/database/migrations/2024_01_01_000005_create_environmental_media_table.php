<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environmental_media', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('name', 100)->unique()->comment('Nombre del medio: Abiotico, Biotico, Socioeconomico');
            $table->text('description')->nullable()->comment('Descripcion del medio ambiental');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environmental_media');
    }
};
