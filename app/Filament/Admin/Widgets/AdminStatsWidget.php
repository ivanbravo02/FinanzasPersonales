<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\CorteCaja;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $hoy = now()->toDateString();
        $ventasHoy = Venta::whereDate('created_at', $hoy)->where('estado', 'completada');
        $ventasMes = Venta::whereMonth('created_at', now()->month)->where('estado', 'completada');

        return [
            Stat::make('Ventas hoy', '$' . number_format($ventasHoy->sum('total'), 2))
                ->description($ventasHoy->count() . ' transacciones')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Ventas del mes', '$' . number_format($ventasMes->sum('total'), 2))
                ->description($ventasMes->count() . ' transacciones')
                ->color('primary')
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Cajas abiertas', CorteCaja::where('estado', 'abierto')->count())
                ->description('En este momento')
                ->color('warning')
                ->icon('heroicon-o-lock-open'),
            Stat::make('Stock bajo', Producto::whereColumn('stock', '<=', 'stock_minimo')->where('activo', true)->count())
                ->description('Productos por reabastecer')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
