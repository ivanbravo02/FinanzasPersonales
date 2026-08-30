<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductoResource\Pages;
use App\Models\Producto;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-cube'; }
    public static function getNavigationGroup(): ?string { return 'Catálogos'; }
    public static function getModelLabel(): string { return 'Producto'; }
    public static function getPluralModelLabel(): string { return 'Productos'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\Select::make('categoria_id')
                    ->relationship('categoria', 'nombre')
                    ->searchable()->preload()->label('Categoría'),
                Forms\Components\TextInput::make('nombre')->required()->maxLength(255),
                Forms\Components\TextInput::make('codigo')->unique(ignoreRecord: true)->label('Código'),
                Forms\Components\TextInput::make('precio_compra')->numeric()->prefix('$')->label('Precio compra'),
                Forms\Components\TextInput::make('precio_venta')->numeric()->prefix('$')->required()->label('Precio venta'),
                Forms\Components\TextInput::make('stock')->numeric()->required()->label('Stock actual'),
                Forms\Components\TextInput::make('stock_minimo')->numeric()->required()->label('Stock mínimo'),
                Forms\Components\Toggle::make('activo')->default(true),
                Forms\Components\Textarea::make('descripcion')->label('Descripción')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')->searchable()->label('Código'),
                Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('categoria.nombre')->label('Categoría')->badge(),
                Tables\Columns\TextColumn::make('precio_venta')->money('MXN')->sortable()->label('Precio'),
                Tables\Columns\TextColumn::make('stock')->sortable()
                    ->color(fn($state, $record) => $record->stockBajo() ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->relationship('categoria', 'nombre')->label('Categoría'),
                Tables\Filters\TernaryFilter::make('activo'),
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
            'index'  => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit'   => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
