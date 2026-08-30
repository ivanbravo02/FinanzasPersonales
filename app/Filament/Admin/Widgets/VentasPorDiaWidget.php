<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Venta;
use Filament\Widgets\ChartWidget;

class VentasPorDiaWidget extends ChartWidget
{
    public ?string $heading = 'Ventas últimos 7 días';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $datos = collect(range(6, 0))->map(function ($diasAtras) {
            $fecha = now()->subDays($diasAtras);
            return [
                'fecha' => $fecha->format('d/m'),
                'total' => Venta::whereDate('created_at', $fecha->toDateString())
                    ->where('estado', 'completada')->sum('total'),
            ];
        });

        return [
            'datasets' => [[
                'label'       => 'Ventas ($)',
                'data'        => $datos->pluck('total')->toArray(),
                'borderColor' => '#6366f1',
                'fill'        => false,
            ]],
            'labels' => $datos->pluck('fecha')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
