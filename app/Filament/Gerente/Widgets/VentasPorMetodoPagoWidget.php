<?php

namespace App\Filament\Gerente\Widgets;

use App\Models\Venta;
use Filament\Widgets\ChartWidget;

class VentasPorMetodoPagoWidget extends ChartWidget
{
    public ?string $heading = 'Ventas por método de pago (mes)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $metodos = ['efectivo', 'tarjeta', 'transferencia'];
        $totales = collect($metodos)->map(fn($m) =>
            Venta::whereMonth('created_at', now()->month)
                ->where('estado', 'completada')
                ->where('metodo_pago', $m)
                ->sum('total')
        );

        return [
            'datasets' => [[
                'data'            => $totales->toArray(),
                'backgroundColor' => ['#10b981', '#f59e0b', '#3b82f6'],
            ]],
            'labels' => ['Efectivo', 'Tarjeta', 'Transferencia'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
