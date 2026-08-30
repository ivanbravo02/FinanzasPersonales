<?php

namespace App\Filament\Gerente\Widgets;

use App\Models\Venta;
use Filament\Widgets\ChartWidget;

class VentasPorDiaWidget extends ChartWidget
{
    public ?string $heading = 'Ventas últimos 30 días';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $datos = collect(range(29, 0))->map(function ($diasAtras) {
            $fecha = now()->subDays($diasAtras);
            return [
                'fecha' => $fecha->format('d/m'),
                'total' => Venta::whereDate('created_at', $fecha->toDateString())
                    ->where('estado', 'completada')->sum('total'),
            ];
        });

        return [
            'datasets' => [[
                'label'           => 'Ventas ($)',
                'data'            => $datos->pluck('total')->toArray(),
                'backgroundColor' => 'rgba(245,158,11,0.2)',
                'borderColor'     => '#f59e0b',
                'fill'            => true,
            ]],
            'labels' => $datos->pluck('fecha')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
