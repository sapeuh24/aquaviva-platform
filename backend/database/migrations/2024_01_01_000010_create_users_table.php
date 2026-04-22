<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa principal a la que pertenece el usuario');
            $table->foreignId('document_type_id')
                ->constrained('document_types')
                ->restrictOnDelete();
            $table->string('document_number', 30)->comment('Numero de documento de identidad');
            $table->string('first_name', 100)->comment('Nombres');
            $table->string('last_name', 100)->comment('Apellidos');
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('phone', 20)->nullable()->comment('Telefono movil');
            $table->boolean('is_active')->default(true)->comment('Estado del usuario');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_users_company_id');
            $table->index('document_type_id', 'idx_users_document_type_id');
            $table->index('document_number', 'idx_users_document_number');
            $table->index('is_active', 'idx_users_is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
