<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nombre_completo',
        'identificacion',
        'puesto',
        'tipo_contrato',
        'fecha_ingreso',
        'salario',
        'periodo_salario',
        'tipo_salario',
        'jornada',
    ];

    // ✅ RELACIÓN (esto te faltaba)
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}