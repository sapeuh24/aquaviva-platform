<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->string('name', 200)->comment('Razon social de la empresa');
            $table->string('nit', 20)->unique()->comment('NIT con digito de verificacion, ej: 900123456-1');
            $table->string('ciiu_code', 20)->nullable()->comment('Codigo CIIU de actividad economica — texto libre alfanumerico (ej: 3511, D351)');
            $table->string('ciiu_description', 255)->nullable()->comment('Descripcion libre de la actividad economica CIIU');
            $table->foreignId('municipality_id')
                ->nullable()
                ->constrained('municipalities')
                ->nullOnDelete();
            $table->string('address', 300)->nullable()->comment('Direccion sede principal');
            $table->string('phone', 20)->nullable()->comment('Telefono de contacto');
            $table->string('email', 150)->nullable()->comment('Email de contacto corporativo');
            $table->string('logo_path', 500)->nullable()->comment('Path del logo en el storage');
            $table->boolean('is_active')->default(true)->comment('Estado de la empresa en la plataforma');
            $table->timestamps();
            $table->softDeletes();

            $table->index('municipality_id', 'idx_companies_municipality_id');
            $table->index('is_active', 'idx_companies_is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
