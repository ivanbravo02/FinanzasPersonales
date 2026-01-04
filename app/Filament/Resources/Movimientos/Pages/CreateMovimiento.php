<?php

namespace App\Filament\Resources\Movimientos\Pages;

use App\Filament\Resources\Movimientos\MovimientoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;

     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate(){
        Notification::make()
        ->title('Movimiento creado')
        ->body('Creado exitosamente')
        ->success()
        ->send();
    }

    protected function getFormActions(): array
    {
        return[
        $this->getCreateFormAction()
        ->label('Registrar'),

        //$this->getCreateAnotherFormAction()
        //->label('Guardar y nuevo'),

        $this->getCancelFormAction()
        ->label('Cancelar'),
        ];
    }
}
