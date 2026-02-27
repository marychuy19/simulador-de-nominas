<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre_razon_social',
        'rfc',
        'direccion_fiscal',
        'regimen_fiscal',
        'periodo_pago',
        'registro_patronal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}