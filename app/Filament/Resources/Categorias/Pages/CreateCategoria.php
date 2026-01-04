<?php

namespace App\Filament\Resources\Categorias\Pages;

use App\Filament\Resources\Categorias\CategoriaResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

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
        ->title('Categoria creada')
        ->body('Creada exitosamente')
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
