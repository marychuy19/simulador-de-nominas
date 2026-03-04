<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsrTarifa extends Model
{
    protected $table = 'isr_tarifas';

    protected $fillable = [
        'tipo',
        'limite_inferior',
        'limite_superior',
        'cuota_fija',
        'porcentaje',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}