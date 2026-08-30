<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoriaProductoResource\Pages;
use App\Models\CategoriaProducto;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CategoriaProductoResource extends Resource
{
    protected static ?string $model = CategoriaProducto::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-tag'; }
    public static function getNavigationGroup(): ?string { return 'Catálogos'; }
    public static function getModelLabel(): string { return 'Categoría'; }
    public static function getPluralModelLabel(): string { return 'Categorías'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('nombre')->required()->maxLength(100),
            Forms\Components\ColorPicker::make('color')->default('#6366f1'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('productos_count')->counts('productos')->label('Productos'),
            ])
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
            'index'  => Pages\ListCategoriaProductos::route('/'),
            'create' => Pages\CreateCategoriaProducto::route('/create'),
            'edit'   => Pages\EditCategoriaProducto::route('/{record}/edit'),
        ];
    }
}
