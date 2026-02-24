<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #000; padding:6px; }
    th { background:#f2f2f2; }
    .right { text-align:right; }
  </style>
</head>
<body>

<h3>Reporte de Nóminas</h3>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Empresa</th>
      <th>Empleado</th>
      <th>Tipo</th>
      <th class="right">Total percepciones (ISR)</th>
      <th class="right">IMSS</th>
      <th class="right">ISR</th>
      <th class="right">Líquido</th>
      <th>Fecha</th>
    </tr>
  </thead>
  <tbody>
    @foreach($calculos as $c)
      @php
        $e = $c->empleado;
        $emp = $e?->empresa;
        $isr = $e?->latestIsr;

        $tp = (float) ($isr?->total_percepciones ?? 0);
        $imss = (float) ($c->total_imss ?? 0);
        $isrRet = (float) ($isr?->isr_retener ?? 0);

        $liq = $tp - $imss - $isrRet;
      @endphp
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $emp?->nombre_razon_social ?? '—' }}</td>
        <td>{{ $e?->nombre_completo ?? '—' }}</td>
        <td>{{ $e?->periodo_salario ?? '—' }}</td>

        <td class="right">$ {{ number_format($tp, 2) }}</td>
        <td class="right">$ {{ number_format($imss, 2) }}</td>
        <td class="right">$ {{ number_format($isrRet, 2) }}</td>
        <td class="right">$ {{ number_format($liq, 2) }}</td>

        <td>{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

</body>
</html>