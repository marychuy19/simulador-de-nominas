<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_razon_social');
            $table->string('rfc', 13)->unique();
            $table->text('direccion_fiscal')->nullable();
            $table->string('regimen_fiscal')->nullable();
            $table->string('periodo_pago')->nullable();
            $table->string('registro_patronal')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
};