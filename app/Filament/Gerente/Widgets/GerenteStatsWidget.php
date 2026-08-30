<?php

namespace App\Filament\Gerente\Widgets;

use App\Models\CorteCaja;
use App\Models\Producto;
use App\Models\Venta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GerenteStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $hoy = now()->toDateString();
        $ventasHoy = Venta::whereDate('created_at', $hoy)->where('estado', 'completada');
        $ventasMes = Venta::whereMonth('created_at', now()->month)->where('estado', 'completada');
        $ventasAyer = Venta::whereDate('created_at', now()->subDay()->toDateString())->where('estado', 'completada');

        $totalHoy = $ventasHoy->sum('total');
        $totalAyer = $ventasAyer->sum('total');
        $diferencia = $totalAyer > 0 ? round((($totalHoy - $totalAyer) / $totalAyer) * 100, 1) : 0;

        return [
            Stat::make('Ventas hoy', '$' . number_format($totalHoy, 2))
                ->description(($diferencia >= 0 ? '+' : '') . $diferencia . '% vs ayer')
                ->color($diferencia >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Ventas del mes', '$' . number_format($ventasMes->sum('total'), 2))
                ->description($ventasMes->count() . ' transacciones')
                ->color('primary')
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Ticket promedio hoy', '$' . number_format($ventasHoy->count() > 0 ? $totalHoy / $ventasHoy->count() : 0, 2))
                ->description('Por transacción')
                ->color('info')
                ->icon('heroicon-o-receipt-percent'),
            Stat::make('Productos con stock bajo', Producto::whereColumn('stock', '<=', 'stock_minimo')->where('activo', true)->count())
                ->description('Requieren reabastecimiento')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
