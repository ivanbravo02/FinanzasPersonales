<?php

namespace App\Filament\Admin\Resources\VentaResource\Pages;

use App\Filament\Admin\Resources\VentaResource;
use App\Models\CorteCaja;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $corte = CorteCaja::where('user_id', auth()->id())->where('estado', 'abierto')->latest()->first();
        $data['corte_caja_id'] = $corte?->id;
        return $data;
    }

    protected function afterCreate(): void
    {
        $venta = $this->record;
        foreach ($venta->items as $item) {
            $item->producto?->decrement('stock', $item->cantidad);
        }
    }
}
