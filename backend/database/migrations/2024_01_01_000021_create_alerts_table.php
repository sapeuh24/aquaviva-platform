<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa duena de la alerta (desnormalizado para multi-tenancy)');
            $table->foreignId('indicator_id')
                ->constrained('indicators')
                ->restrictOnDelete()
                ->comment('Indicador que genera la alerta');
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->comment('Usuario notificado (NULL = todos los responsables del indicador)');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->string('type', 50)->comment('Tipo de alerta: vencimiento, incumplimiento, recordatorio');
            $table->date('due_date')->comment('Fecha limite o fecha del evento que genera la alerta');
            $table->string('status', 30)->default('activa')
                ->comment('Estado: activa, vista, resuelta, ignorada');
            $table->text('message')->nullable()->comment('Mensaje descriptivo de la alerta');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_alerts_company_id');
            $table->index('indicator_id', 'idx_alerts_indicator_id');
            $table->index('user_id', 'idx_alerts_user_id');
            $table->index('status', 'idx_alerts_status');
            $table->index('due_date', 'idx_alerts_due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
