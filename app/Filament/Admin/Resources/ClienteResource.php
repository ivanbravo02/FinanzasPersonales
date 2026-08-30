<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-users'; }
    public static function getNavigationGroup(): ?string { return 'Catálogos'; }
    public static function getModelLabel(): string { return 'Cliente'; }
    public static function getPluralModelLabel(): string { return 'Clientes'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('nombre')->required()->maxLength(255),
            Forms\Components\TextInput::make('telefono')->tel()->maxLength(20),
            Forms\Components\TextInput::make('email')->email()->maxLength(255),
            Forms\Components\TextInput::make('rfc')->maxLength(13)->label('RFC'),
            Forms\Components\Toggle::make('activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('telefono')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('rfc')->label('RFC'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
                Tables\Columns\TextColumn::make('ventas_count')->counts('ventas')->label('Ventas'),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('activo')])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit'   => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
