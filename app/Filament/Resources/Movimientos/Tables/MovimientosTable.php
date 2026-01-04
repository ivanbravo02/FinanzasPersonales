<?php

namespace App\Filament\Resources\Movimientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class MovimientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('Nro'),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
                TextColumn::make('categoria.nombre')
                    ->label('Categoria')
                    ->sortable(),
                TextColumn::make('tipo')
                    ->label('Tipo de movimiento')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->html()
                    ->limit(50)
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('foto')
                    ->searchable()
                    ->width(100)
                    ->disk('public')
                    ->height(100),
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo')
                ->options([
                    'ingreso'=> 'Ingreso',
                    'gasto' => 'Gasto',
                ])
                ->placeholder('Filtrar por Tipo')
                ->label('Tipo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                ->button()
                ->color('primary'),
                DeleteAction::make()
                ->button()
                ->color('danger')
                ->successNotification(
                    Notification::make()
                        ->title('Movimiento eliminado')
                        ->body('Eliminado exitosamente')
                        ->success()
                ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
