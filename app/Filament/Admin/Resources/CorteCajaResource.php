<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CorteCajaResource\Pages;
use App\Models\CorteCaja;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CorteCajaResource extends Resource
{
    protected static ?string $model = CorteCaja::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-calculator'; }
    public static function getNavigationGroup(): ?string { return 'Ventas'; }
    public static function getModelLabel(): string { return 'Corte de caja'; }
    public static function getPluralModelLabel(): string { return 'Cortes de caja'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')->required()->label('Cajero'),
                Forms\Components\TextInput::make('fondo_inicial')->numeric()->prefix('$')->required()->label('Fondo inicial'),
                Forms\Components\TextInput::make('efectivo_contado')->numeric()->prefix('$')->label('Efectivo contado'),
                Forms\Components\Select::make('estado')
                    ->options(['abierto' => 'Abierto', 'cerrado' => 'Cerrado'])->required(),
                Forms\Components\TextInput::make('total_ventas')->numeric()->prefix('$')->readOnly()->label('Total ventas'),
                Forms\Components\TextInput::make('total_efectivo')->numeric()->prefix('$')->readOnly()->label('Total efectivo'),
                Forms\Components\TextInput::make('total_tarjeta')->numeric()->prefix('$')->readOnly()->label('Total tarjeta'),
                Forms\Components\TextInput::make('total_transferencia')->numeric()->prefix('$')->readOnly()->label('Total transferencia'),
                Forms\Components\TextInput::make('diferencia')->numeric()->prefix('$')->readOnly(),
                Forms\Components\Textarea::make('notas')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Cajero')->searchable(),
                Tables\Columns\TextColumn::make('abierto_en')->dateTime('d/m/Y H:i')->label('Apertura')->sortable(),
                Tables\Columns\TextColumn::make('cerrado_en')->dateTime('d/m/Y H:i')->label('Cierre')->default('—'),
                Tables\Columns\TextColumn::make('fondo_inicial')->money('MXN')->label('Fondo'),
                Tables\Columns\TextColumn::make('total_ventas')->money('MXN')->label('Total ventas'),
                Tables\Columns\TextColumn::make('diferencia')->money('MXN')
                    ->color(fn($state) => ($state ?? 0) < 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('estado')->badge()
                    ->color(fn($state) => $state === 'abierto' ? 'success' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCortesCaja::route('/'),
            'create' => Pages\CreateCorteCaja::route('/create'),
            'view'   => Pages\ViewCorteCaja::route('/{record}'),
            'edit'   => Pages\EditCorteCaja::route('/{record}/edit'),
        ];
    }
}
