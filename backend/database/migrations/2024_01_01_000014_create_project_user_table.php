<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete()
                ->comment('Proyecto al que se asigna el responsable');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Usuario responsable del proyecto');
            $table->string('role_in_project', 100)->nullable()->comment('Rol en el proyecto: Coordinador, Analista, etc.');
            $table->timestamps();

            $table->unique(['project_id', 'user_id'], 'uq_project_user');
            $table->index('user_id', 'idx_project_user_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
