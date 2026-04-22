<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migracion v1.1 — Reunion cliente 2024-01-06
 *
 * Punto 4: "Arreglar organigrama — no funciona al agregar usuario nuevo"
 *
 * Analisis: En el sistema anterior existia la tabla `structure` con campos:
 *   - employment (cargo/puesto)
 *   - id_father (nodo padre en el arbol jerarquico)
 *   - id_mate (nodo hermano — relacion de pares)
 *   - id_area (area organizacional)
 *   - id_user (usuario asignado al nodo)
 *
 * Problemas del diseno anterior:
 * - `id_father` y `id_mate` son BIGINT nullable sin FK real (error de integridad)
 * - Dependencia de `area` tabla que fue eliminada del nuevo esquema
 * - El campo `id_mate` (hermano) es una relacion rara — normalmente el arbol se
 *   representa solo con parent_id y el orden de los hermanos con un campo `order`
 *
 * Nuevo diseno: arbol jerarquico simple (modelo Adjacency List) con:
 * - parent_id: nodo padre (NULL = raiz)
 * - user_id: usuario asignado (NULLABLE — puede haber nodos sin usuario asignado aun)
 * - position_title: cargo o posicion (texto libre)
 * - department_name: nombre del area/departamento (texto libre, sin FK a tabla separada)
 * - order: orden de presentacion entre hermanos
 *
 * Por que Adjacency List y no Nested Sets:
 * - Las empresas cliente son medianas (max ~100 nodos en el arbol)
 * - Adjacency List es mas simple de mantener via CRUD standard
 * - MySQL 8 soporta CTEs recursivas para queries de arbol completo
 *
 * El problema del cliente ("no funciona al agregar usuario nuevo") probablemente era
 * que el sistema anterior intentaba crear un nodo en `structure` pero fallaba porque
 * `id_area` era requerida y la tabla `area` no tenia datos. Con este nuevo diseno,
 * `department_name` es texto libre opcional, eliminando la dependencia.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_chart_positions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->comment('Empresa a la que pertenece este organigrama');
            // Self-referential FK para estructura de arbol
            $table->unsignedBigInteger('parent_id')->nullable()
                ->comment('Nodo padre en el organigrama (NULL = nodo raiz)');
            $table->foreign('parent_id', 'fk_org_chart_parent')
                ->references('id')
                ->on('org_chart_positions')
                ->nullOnDelete();
            // Usuario asignado al nodo (NULLABLE: puede existir el cargo sin usuario asignado)
            $table->unsignedBigInteger('user_id')->nullable()
                ->comment('Usuario asignado a esta posicion (NULL = cargo vacante)');
            $table->foreign('user_id', 'fk_org_chart_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->string('position_title', 200)->comment('Titulo del cargo o posicion: Director, Coordinador, Analista, etc.');
            $table->string('department_name', 200)->nullable()->comment('Nombre del area o departamento — texto libre');
            $table->unsignedSmallInteger('order')->default(0)->comment('Orden de presentacion entre nodos del mismo nivel');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id', 'idx_org_chart_company_id');
            $table->index('parent_id', 'idx_org_chart_parent_id');
            $table->index('user_id', 'idx_org_chart_user_id');
            // Indice compuesto para cargar todos los nodos de una empresa ordenados
            $table->index(['company_id', 'parent_id', 'order'], 'idx_org_chart_company_parent_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_chart_positions');
    }
};
