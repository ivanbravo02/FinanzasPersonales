<?php

namespace App\Filament\Cajero\Widgets;

use App\Models\CorteCaja;
use App\Models\Venta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CajeroStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $corte = CorteCaja::where('user_id', auth()->id())
            ->where('estado', 'abierto')->latest()->first();

        if (!$corte) {
            return [
                Stat::make('Estado de caja', 'CERRADA')
                    ->description('Abre la caja para vender')
                    ->color('danger')
                    ->icon('heroicon-o-lock-closed'),
            ];
        }

        $ventas = $corte->ventas()->where('estado', 'completada');

        return [
            Stat::make('Caja', 'ABIERTA')
                ->description('Desde ' . $corte->abierto_en->format('H:i'))
                ->color('success')
                ->icon('heroicon-o-lock-open'),
            Stat::make('Ventas del corte', $ventas->count())
                ->description('Transacciones completadas')
                ->color('primary')
                ->icon('heroicon-o-shopping-cart'),
            Stat::make('Total recaudado', '$' . number_format($ventas->sum('total'), 2))
                ->description('Efectivo: $' . number_format($ventas->where('metodo_pago', 'efectivo')->sum('total'), 2))
                ->color('success')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
