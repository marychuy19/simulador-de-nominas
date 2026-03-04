<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('isr_tarifas', function (Blueprint $table) {
            $table->id();

            // Para soportar "diaria, semanal, quincenal, mensual" etc.
            $table->string('tipo', 20); // diaria|semanal|decenal|quincenal|mensual (o como lo manejes)

            $table->decimal('limite_inferior', 12, 2);
            $table->decimal('limite_superior', 12, 2)->nullable(); // null = "en adelante"
            $table->decimal('cuota_fija', 12, 2);
            $table->decimal('porcentaje', 6, 4); // Ej 1.92 o 30.00 (tú decides el formato, abajo te digo)

            $table->unsignedInteger('orden')->default(1); // para ordenar renglones
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index(['tipo', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isr_tarifas');
    }
};