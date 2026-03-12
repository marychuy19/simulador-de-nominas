<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionNomina extends Model
{
    protected $table = 'configuraciones_nomina';

    protected $fillable = [
        'salario_minimo',
        'uma',
        'limite_vales_despensa',
        'limite_excento_vales',
        'subsidio_empleo',
        'tope_subsidio',
      
    ];
}