<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Isr;
use App\Models\IsrTarifa;
use App\Models\ConfiguracionNomina;
use App\Models\CuotaImss;

class NominaController extends Controller
{
    private function getNominaSharedData(string $tipo): array
    {
        $tipoTabla = match ($tipo) {
            'decena' => 'decenal',
            default => $tipo,
        };

        $config = ConfiguracionNomina::first();

        if (!$config) {
            $config = ConfiguracionNomina::create([
                'salario_minimo' => 278.80,
                'uma' => 117.31,
                'limite_vales_despensa' => 0.40,
                'subsidio_empleo' => 15.02,
                'tope_subsidio' => 535.65,
                'tope_subsidio_mensual' => 535.65,
                'limite_ingreso_subsidio' => 11492.66,
            ]);
        }

        $cuotas = CuotaImss::first();

        if (!$cuotas) {
            $cuotas = CuotaImss::create([
                'excedente_patronal' => 0.0040,
                'prestaciones_dinero' => 0.0025,
                'prestaciones_especie' => 0.00375,
                'invalidez_vida' => 0.00625,
                'cesantia_vejez' => 0.01125,
            ]);
        }

        $tablaIsr = IsrTarifa::where('tipo', $tipoTabla)
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('limite_inferior')
            ->get([
                'id',
                'limite_inferior',
                'limite_superior',
                'cuota_fija',
                'porcentaje',
                'orden',
                'activo',
            ]);

        return [
            'configNomina' => $config,
            'cuotasImss' => $cuotas,
            'tablaIsr' => $tablaIsr,
        ];
    }

    public function diaria()
    {
        return Inertia::render('Alumno/Nomina/Diaria', $this->getNominaSharedData('diaria'));
    }

    public function semanal()
    {
        return Inertia::render('Alumno/Nomina/Semanal', $this->getNominaSharedData('semanal'));
    }

    public function decena()
    {
        return Inertia::render('Alumno/Nomina/Decena', $this->getNominaSharedData('decena'));
    }

    public function quincenal()
    {
        return Inertia::render('Alumno/Nomina/Quincenal', $this->getNominaSharedData('quincenal'));
    }

    public function mensual()
    {
        return Inertia::render('Alumno/Nomina/Mensual', $this->getNominaSharedData('mensual'));
    }

    public function diaria2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Diaria2', array_merge(
            $this->getNominaSharedData('diaria'),
            [
                'prefil1' => [
                    'empresa' => $request->query('empresa', ''),
                    'empleado' => $request->query('empleado', ''),
                    'salario_base' => $request->query('salario_base', 0),
                    'dias_trabajados' => $request->query('dias_trabajados', 1),
                ],
            ]
        ));
    }

    public function semanal2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Semanal2', array_merge(
            $this->getNominaSharedData('semanal'),
            [
                'prefil2' => [
                    'empresa' => $request->query('empresa', ''),
                    'empleado' => $request->query('empleado', ''),
                    'salario_base' => $request->query('salario_base', 0),
                    'dias_trabajados' => $request->query('dias_trabajados', 7),
                ],
            ]
        ));
    }

    public function decena2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Decena2', array_merge(
            $this->getNominaSharedData('decena'),
            [
                'prefil3' => [
                    'empresa' => $request->query('empresa', ''),
                    'empleado' => $request->query('empleado', ''),
                    'salario_base' => $request->query('salario_base', 0),
                    'dias_trabajados' => $request->query('dias_trabajados', 10),
                ],
            ]
        ));
    }

    public function quincenal2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Quincenal2', array_merge(
            $this->getNominaSharedData('quincenal'),
            [
                'prefill' => [
                    'empresa' => $request->query('empresa', ''),
                    'empleado' => $request->query('empleado', ''),
                    'salario_base' => $request->query('salario_base', 0),
                    'dias_trabajados' => $request->query('dias_trabajados', 15),
                ],
            ]
        ));
    }

    public function mensual2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Mensual2', array_merge(
            $this->getNominaSharedData('mensual'),
            [
                'prefil4' => [
                    'empresa' => $request->query('empresa', ''),
                    'empleado' => $request->query('empleado', ''),
                    'salario_base' => $request->query('salario_base', 0),
                    'dias_trabajados' => $request->query('dias_trabajados', 30),
                ],
            ]
        ));
    }

    public function guardarIsr(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'salario_base' => 'required|numeric',
            'dias_trabajados' => 'required|numeric',
            'total_percepciones' => 'required|numeric',
            'isr_determinado' => 'required|numeric',
            'subsidio_periodo' => 'required|numeric',
            'isr_retener' => 'required|numeric',
        ]);

        Isr::create($request->all());

        return redirect()->route('alumno.recibo');
    }
}