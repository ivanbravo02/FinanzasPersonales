<?php

namespace App\Filament\Admin\Resources\CorteCajaResource\Pages;

use App\Filament\Admin\Resources\CorteCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCortesCaja extends ListRecords
{
    protected static string $resource = CorteCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
