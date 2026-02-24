<?php

namespace App\Exports;

use App\Models\CalculoNomina;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NominasAllExport implements FromArray, WithHeadings
{
    public function __construct(private array $filters = []) {}

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
        $q = CalculoNomina::query()->with(['empleado.empresa','empleado.latestIsr'])->latest();

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $q->whereHas('empleado', function ($qq) use ($search) {
                $qq->where('nombre_completo','like',"%{$search}%")
                   ->orWhereHas('empresa', fn($qe)=>$qe->where('nombre_razon_social','like',"%{$search}%"));
            });
        }
        if (!empty($this->filters['empresa'])) {
            $empresa = $this->filters['empresa'];
            $q->whereHas('empleado.empresa', fn($qe)=>$qe->where('nombre_razon_social','like',"%{$empresa}%"));
        }
        if (!empty($this->filters['tipo'])) {
            $tipo = $this->filters['tipo'];
            $q->whereHas('empleado', fn($qq)=>$qq->where('periodo_salario',$tipo));
        }

        return $q->get()->map(function ($c) {
            $e=$c->empleado; $emp=$e?->empresa; $isr=$e?->latestIsr;

            return [
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
            ];
        })->toArray();
    }
}