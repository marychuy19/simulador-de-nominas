<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isr', function (Blueprint $table) {
            $table->id();

            // 🔗 Relación con empleados
            $table->foreignId('empleado_id')
                  ->constrained('empleados')
                  ->cascadeOnDelete();

            // 📊 Datos de cálculo
            $table->decimal('salario_base', 10, 2);
            $table->integer('dias_trabajados');

            $table->decimal('total_percepciones', 10, 2);
            $table->decimal('isr_determinado', 10, 2);
            $table->decimal('subsidio_periodo', 10, 2);
            $table->decimal('isr_retener', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isr');
    }
};
