<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete()
                ->comment('Empresa a la que se otorga acceso');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Usuario con acceso extendido');
            $table->timestamp('granted_at')->nullable()->comment('Momento en que se otorgo el acceso');
            $table->timestamps();

            $table->unique(['company_id', 'user_id'], 'uq_company_user');
            $table->index('user_id', 'idx_company_user_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
    }
};
