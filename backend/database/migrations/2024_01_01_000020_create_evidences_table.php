<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la evidencia (desnormalizado para multi-tenancy)');
            $table->foreignId('activity_id')
                ->constrained('activities')
                ->restrictOnDelete()
                ->comment('Actividad a la que pertenece esta evidencia');
            $table->unsignedBigInteger('uploaded_by')
                ->nullable()
                ->comment('Usuario que subio el archivo');
            $table->foreign('uploaded_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->string('original_name', 255)->comment('Nombre original del archivo tal como fue subido');
            $table->string('storage_path', 500)->comment('Path relativo en el storage de la aplicacion');
            $table->string('mime_type', 100)->comment('Tipo MIME del archivo: image/jpeg, application/pdf, etc.');
            $table->unsignedBigInteger('size_bytes')->comment('Tamano del archivo en bytes');
            $table->text('description')->nullable()->comment('Descripcion o comentario sobre la evidencia');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_evidences_company_id');
            $table->index('activity_id', 'idx_evidences_activity_id');
            $table->index('uploaded_by', 'idx_evidences_uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
