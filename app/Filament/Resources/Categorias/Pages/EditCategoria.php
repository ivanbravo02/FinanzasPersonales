<?php

namespace App\Filament\Resources\Categorias\Pages;

use App\Filament\Resources\Categorias\CategoriaResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function afterSave(): void {
        Notification::make()
        ->title('Categoria actualizada')
        ->body('Actualizada exitosamente')
        ->success()
        ->send();
    }

    protected function getHeaderActions(): array
    {
    
        return [
            
            DeleteAction::make()
            ->successNotification(
                 Notification::make()
                ->title('Categoria eliminada')
                ->body('Eliminada exitosamente')
                ->success()
            ),
        ];
    }
}
