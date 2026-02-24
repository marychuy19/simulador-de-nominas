<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Isr;


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

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function calculosNomina()
{
    return $this->hasMany(CalculoNomina::class);
}

public function isrs()
{
    return $this->hasMany(Isr::class);
}

public function latestIsr()
{
    return $this->hasOne(Isr::class)->latestOfMany();
}

}
