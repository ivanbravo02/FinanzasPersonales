<?php

namespace App\Filament\Cajero\Resources\VentaResource\Pages;

use App\Filament\Cajero\Resources\VentaResource;
use App\Models\CorteCaja;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListVentas extends ListRecords
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        $corteAbierto = CorteCaja::where('user_id', auth()->id())
            ->where('estado', 'abierto')->exists();

        if (!$corteAbierto) {
            return [
                Actions\Action::make('abrir_caja')
                    ->label('Abrir caja para vender')
                    ->icon('heroicon-o-lock-open')
                    ->color('warning')
                    ->url(fn() => route('filament.cajero.pages.corte-caja')),
            ];
        }

        return [Actions\CreateAction::make()->label('Nueva venta')];
    }
}
