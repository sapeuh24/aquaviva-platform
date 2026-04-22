<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name')
                ->comment('Descripcion detallada de la actividad');
            $table->string('compliance_status', 30)->default('pendiente')->after('executed_date')
                ->comment('Estado de cumplimiento: pendiente, en_progreso, cumplida, incumplida');
            $table->text('notes')->nullable()->after('compliance_status')
                ->comment('Notas adicionales de seguimiento');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['description', 'compliance_status', 'notes']);
        });
    }
};
