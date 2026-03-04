<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_nomina', function (Blueprint $table) {
            $table->id();

            $table->decimal('salario_minimo',10,2)->default(0);
            $table->decimal('uma',10,2)->default(0);
            $table->decimal('limite_vales_despensa',10,2)->default(0);

            $table->decimal('subsidio_empleo',10,2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_nomina');
    }
};