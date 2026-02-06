<h2>Relatório de Pedidos</h2>

<p>
Período: {{ $report['period']['from'] }}
até {{ $report['period']['to'] }}
</p>

<p>Total de pedidos: <strong>{{ $report['total'] }}</strong></p>

<ul>
@foreach ($report['by_status'] as $status => $count)
    <li>{{ $status }}: {{ $count }}</li>
@endforeach
</ul>
