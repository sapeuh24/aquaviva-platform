<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migracion v1.1 — Reunion cliente 2024-01-06
 *
 * Agrega la FK de worksheets.obligation_id → obligations.id
 * Esta migracion corre despues de que obligations fue creada (2024_01_02_000002),
 * resolviendo la dependencia circular de orden entre las migraciones.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->foreign('obligation_id', 'fk_worksheets_obligation_id')
                ->references('id')
                ->on('obligations')
                ->nullOnDelete();

            $table->index('obligation_id', 'idx_worksheets_obligation_id_fk');
        });
    }

    public function down(): void
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropForeign('fk_worksheets_obligation_id');
            $table->dropIndex('idx_worksheets_obligation_id_fk');
        });
    }
};
