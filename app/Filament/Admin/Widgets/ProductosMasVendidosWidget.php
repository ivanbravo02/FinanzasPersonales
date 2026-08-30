<?php

namespace App\Filament\Admin\Widgets;

use App\Models\VentaItem;
use Filament\Widgets\ChartWidget;

class ProductosMasVendidosWidget extends ChartWidget
{
    public ?string $heading = 'Top 5 productos más vendidos (mes)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $top = VentaItem::selectRaw('nombre_producto, SUM(cantidad) as total_vendido')
            ->whereHas('venta', fn($q) => $q->where('estado', 'completada')
                ->whereMonth('created_at', now()->month))
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return [
            'datasets' => [[
                'label'           => 'Unidades vendidas',
                'data'            => $top->pluck('total_vendido')->toArray(),
                'backgroundColor' => ['#6366f1', '#f59e0b', '#10b981', '#ef4444', '#3b82f6'],
            ]],
            'labels' => $top->pluck('nombre_producto')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
