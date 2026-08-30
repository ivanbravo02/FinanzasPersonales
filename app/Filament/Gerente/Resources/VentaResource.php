<?php

namespace App\Filament\Gerente\Resources;

use App\Filament\Gerente\Resources\VentaResource\Pages;
use App\Models\Venta;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-shopping-cart'; }
    public static function getModelLabel(): string { return 'Venta'; }
    public static function getPluralModelLabel(): string { return 'Ventas'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folio')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Fecha')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Cajero')->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')->label('Cliente')->default('Público general'),
                Tables\Columns\TextColumn::make('subtotal')->money('MXN'),
                Tables\Columns\TextColumn::make('descuento')->money('MXN'),
                Tables\Columns\TextColumn::make('total')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('metodo_pago')->badge()
                    ->color(fn($state) => match($state) {
                        'efectivo' => 'success', 'tarjeta' => 'warning', default => 'info'
                    })->label('Pago'),
                Tables\Columns\TextColumn::make('estado')->badge()
                    ->color(fn($state) => $state === 'completada' ? 'success' : 'danger'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(['completada' => 'Completada', 'cancelada' => 'Cancelada']),
                Tables\Filters\SelectFilter::make('metodo_pago')
                    ->options(['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'])
                    ->label('Método de pago'),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(fn($query, array $data) => $query
                        ->when($data['desde'], fn($q, $v) => $q->whereDate('created_at', '>=', $v))
                        ->when($data['hasta'], fn($q, $v) => $q->whereDate('created_at', '<=', $v))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
            'view'  => Pages\ViewVenta::route('/{record}'),
        ];
    }
}
