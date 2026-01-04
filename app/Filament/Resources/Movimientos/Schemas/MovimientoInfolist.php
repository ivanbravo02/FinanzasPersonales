<?php

namespace App\Filament\Resources\Movimientos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovimientoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->sortable()
                    ->label('Nro')
                    ->rowIndex(),
                TextEntry::make('categoria_id')
                    ->numeric(),
                TextEntry::make('tipo')
                    ->badge(),
                TextEntry::make('monto')
                    ->numeric(),
                TextEntry::make('descripcion')
                    ->columnSpanFull(),
                TextEntry::make('foto')
                    ->placeholder('-'),
                TextEntry::make('fecha')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
