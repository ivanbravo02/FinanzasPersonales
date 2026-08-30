<?php

namespace App\Filament\Admin\Resources\CorteCajaResource\Pages;

use App\Filament\Admin\Resources\CorteCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorteCaja extends EditRecord
{
    protected static string $resource = CorteCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
