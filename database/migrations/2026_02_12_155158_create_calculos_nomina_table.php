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
    Schema::create('calculos_nomina', function (Blueprint $table) {
        $table->id();

        $table->foreignId('empleado_id')
              ->constrained()
              ->onDelete('cascade');

        $table->integer('dias_aguinaldo');
        $table->integer('dias_vacaciones');

        $table->decimal('prima_vacacional', 8, 2);
        $table->decimal('vales_despensa_porcentaje', 8, 2);

        $table->decimal('salario_diario', 10, 2);

        $table->decimal('proporcion_aguinaldo', 10, 2);
        $table->decimal('proporcion_vacaciones', 10, 2);

        $table->decimal('sbc_sin_vales', 10, 2);
        $table->decimal('vales_gravados', 10, 2);
        $table->decimal('sbc_con_vales', 10, 2);

        $table->decimal('total_imss', 10, 2);

        $table->timestamps();
    });
}
};
