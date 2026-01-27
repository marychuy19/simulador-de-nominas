<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->cascadeOnDelete();

            $table->string('nombre_completo');
            $table->string('identificacion');
            $table->string('puesto');
            $table->string('tipo_contrato');
            $table->date('fecha_ingreso');
            $table->decimal('salario', 10, 2);
            $table->string('periodo_salario');
            $table->string('tipo_salario');
            $table->string('jornada');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
