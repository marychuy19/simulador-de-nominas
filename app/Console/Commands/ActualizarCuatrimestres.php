<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ActualizarCuatrimestres extends Command
{
    protected $signature = 'cuatrimestres:actualizar';
    protected $description = 'Avanza los cuatrimestres de los alumnos cada periodo';

    public function handle()
    {
        $mes = now()->month;

        // Solo ejecutar en enero, mayo y septiembre
        if (!in_array($mes, [1,5,9])) {
            return;
        }

        $usuarios = User::where('role','alumno')->get();

        foreach ($usuarios as $user) {

            if ($user->cuatrimestre >= 11) {

                // eliminar si llegó al último
                $user->delete();

            } else {

                // avanzar cuatrimestre
                $user->increment('cuatrimestre');

            }

        }

        $this->info('Cuatrimestres actualizados correctamente');
    }
}