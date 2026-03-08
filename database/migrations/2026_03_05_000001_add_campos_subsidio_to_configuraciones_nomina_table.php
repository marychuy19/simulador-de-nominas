<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuraciones_nomina', function (Blueprint $table) {
            $table->decimal('tope_subsidio_mensual', 10, 2)->default(535.65)->after('subsidio_empleo');
            $table->decimal('limite_ingreso_subsidio', 10, 2)->default(11492.66)->after('tope_subsidio_mensual');
        });
    }

    public function down(): void
    {
        Schema::table('configuraciones_nomina', function (Blueprint $table) {
            $table->dropColumn(['tope_subsidio_mensual', 'limite_ingreso_subsidio']);
        });
    }
};