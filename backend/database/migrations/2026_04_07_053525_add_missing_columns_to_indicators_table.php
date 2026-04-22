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
        Schema::table('indicators', function (Blueprint $table) {
            $table->date('next_due_date')->nullable()->after('target_value')
                ->comment('Proxima fecha de cumplimiento o medicion');
            $table->boolean('is_active')->default(true)->after('next_due_date')
                ->comment('Indica si el indicador esta activo');
        });
    }

    public function down(): void
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn(['next_due_date', 'is_active']);
        });
    }
};
