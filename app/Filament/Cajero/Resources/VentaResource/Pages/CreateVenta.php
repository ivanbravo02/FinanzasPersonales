<?php

namespace App\Filament\Cajero\Resources\VentaResource\Pages;

use App\Filament\Cajero\Resources\VentaResource;
use App\Models\CorteCaja;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    public function mount(): void
    {
        $corte = CorteCaja::where('user_id', auth()->id())->where('estado', 'abierto')->first();

        if (!$corte) {
            Notification::make()->title('Debes abrir la caja antes de vender')->warning()->send();
            $this->redirect(route('filament.cajero.pages.corte-caja'));
            return;
        }

        parent::mount();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['estado'] = 'completada';
        $corte = CorteCaja::where('user_id', auth()->id())->where('estado', 'abierto')->latest()->first();
        $data['corte_caja_id'] = $corte?->id;
        if ($data['metodo_pago'] !== 'efectivo') {
            $data['monto_pagado'] = $data['total'] ?? 0;
            $data['cambio'] = 0;
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->record->items as $item) {
            $item->producto?->decrement('stock', $item->cantidad);
        }

        Notification::make()
            ->title("Venta {$this->record->folio} registrada")
            ->body("Total: $" . number_format($this->record->total, 2))
            ->success()->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
