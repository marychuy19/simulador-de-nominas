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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_razon_social');
            $table->string('rfc', 13)->unique();
            $table->text('direccion_fiscal');
            $table->string('regimen_fiscal');

        
            $table->enum('periodo_pago', [
                'diario',
                'semanal',
                'decenal',
                'quincenal',
                'mensual'
            ]);

            $table->text('registro_patronal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
