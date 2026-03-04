<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuotaImss extends Model
{
    protected $table = 'cuotas_imss';

    protected $fillable = [
        'excedente_patronal',
        'prestaciones_dinero',
        'prestaciones_especie',
        'invalidez_vida',
        'cesantia_vejez',
    ];
}