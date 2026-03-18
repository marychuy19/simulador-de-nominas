<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Isr extends Model
{
    protected $table = 'isr';

    protected $fillable = [
        'empleado_id',
        'salario_base',
        'dias_trabajados',
        'total_percepciones',
        'fecha_inicio_periodo',
        'fecha_termino_periodo',
        'isr_determinado',
        'subsidio_periodo',
        'isr_retener',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
