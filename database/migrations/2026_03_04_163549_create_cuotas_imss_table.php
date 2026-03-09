<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas_imss', function (Blueprint $table) {
            $table->id();
            $table->decimal('excedente_patronal', 12, 6)->default(0);
            $table->decimal('prestaciones_dinero', 12, 6)->default(0);
            $table->decimal('prestaciones_especie', 12, 6)->default(0);
            $table->decimal('invalidez_vida', 12, 6)->default(0);
            $table->decimal('cesantia_vejez', 12, 6)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas_imss');
    }
};