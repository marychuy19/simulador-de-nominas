<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_razon_social',
        'rfc',
        'direccion_fiscal',
        'regimen_fiscal',
        'periodo_pago',
        'registro_patronal',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
