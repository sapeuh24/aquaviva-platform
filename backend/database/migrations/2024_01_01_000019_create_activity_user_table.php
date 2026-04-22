<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete()
                ->comment('Actividad a la que se asigna el responsable');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Usuario responsable de la actividad');
            $table->timestamps();

            $table->unique(['activity_id', 'user_id'], 'uq_activity_user');
            $table->index('user_id', 'idx_activity_user_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_user');
    }
};
