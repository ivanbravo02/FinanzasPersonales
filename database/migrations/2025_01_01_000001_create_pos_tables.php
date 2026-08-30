<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // admin, cajero, gerente
            $table->timestamps();
        });

        // Agregar rol a usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
        });

        // Clientes
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('rfc')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Categorías de productos
        Schema::create('categorias_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color')->default('#6366f1');
            $table->timestamps();
        });

        // Productos
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_productos')->nullOnDelete();
            $table->string('nombre');
            $table->string('codigo')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Cortes de caja
        Schema::create('cortes_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('fondo_inicial', 10, 2)->default(0);
            $table->decimal('efectivo_contado', 10, 2)->nullable();
            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_tarjeta', 10, 2)->default(0);
            $table->decimal('total_transferencia', 10, 2)->default(0);
            $table->decimal('diferencia', 10, 2)->nullable();
            $table->text('notas')->nullable();
            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');
            $table->timestamp('abierto_en')->useCurrent();
            $table->timestamp('cerrado_en')->nullable();
            $table->timestamps();
        });

        // Ventas
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('corte_caja_id')->nullable()->constrained('cortes_caja')->nullOnDelete();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->decimal('cambio', 10, 2)->default(0);
            $table->enum('estado', ['completada', 'cancelada'])->default('completada');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Items de venta
        Schema::create('venta_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained()->cascadeOnDelete();
            $table->string('nombre_producto'); // snapshot
            $table->decimal('precio_unitario', 10, 2);
            $table->integer('cantidad');
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_items');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('cortes_caja');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias_productos');
        Schema::dropIfExists('clientes');
        Schema::table('users', fn(Blueprint $t) => $t->dropForeignIdFor(\App\Models\Role::class));
        Schema::dropIfExists('roles');
    }
};
