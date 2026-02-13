<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculoNomina extends Model
{
    use HasFactory;

    protected $fillable = [
    'empleado_id',
    'dias_aguinaldo',
    'dias_vacaciones',
    'prima_vacacional',
    'vales_despensa_porcentaje',
    'salario_diario',
    'proporcion_aguinaldo',
    'proporcion_vacaciones',
    'sbc_sin_vales',
    'vales_gravados',
    'sbc_con_vales',
    'total_imss',
];

public function empleado()
{
    return $this->belongsTo(Empleado::class);
}


}
