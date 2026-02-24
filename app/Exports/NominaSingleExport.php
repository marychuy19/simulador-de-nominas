<?php

namespace App\Exports;

use App\Models\CalculoNomina;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NominaSingleExport implements FromArray, WithHeadings
{
    public function __construct(private int $id) {}

    public function headings(): array
    {
        return [
            'ID','Empresa','Empleado','Tipo',
            'Salario Diario','Proporción Aguinaldo','Proporción Vacaciones',
            'IMSS Total','ISR Retener','Fecha'
        ];
    }

    public function array(): array
    {
        $c = CalculoNomina::with(['empleado.empresa','empleado.latestIsr'])->findOrFail($this->id);
        $e = $c->empleado; $emp = $e?->empresa; $isr = $e?->latestIsr;

        return [[
            $c->id,
            $emp?->nombre_razon_social,
            $e?->nombre_completo,
            $e?->periodo_salario,
            $c->salario_diario,
            $c->proporcion_aguinaldo,
            $c->proporcion_vacaciones,
            $c->total_imss,
            $isr?->isr_retener ?? 0,
            optional($c->created_at)->format('Y-m-d H:i'),
        ]];
    }
}