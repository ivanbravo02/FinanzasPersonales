<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VentaResource\Pages;
use App\Models\Producto;
use App\Models\Venta;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-shopping-cart'; }
    public static function getNavigationGroup(): ?string { return 'Ventas'; }
    public static function getModelLabel(): string { return 'Venta'; }
    public static function getPluralModelLabel(): string { return 'Ventas'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Datos de la venta')->columns(2)->schema([
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->searchable()->preload()->label('Cliente'),
                Forms\Components\Select::make('metodo_pago')
                    ->options(['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'])
                    ->required()->default('efectivo')->label('Método de pago'),
                Forms\Components\TextInput::make('monto_pagado')->numeric()->prefix('$')->label('Monto pagado'),
                Forms\Components\Select::make('estado')
                    ->options(['completada' => 'Completada', 'cancelada' => 'Cancelada'])
                    ->required()->default('completada'),
                Forms\Components\Textarea::make('notas')->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Productos')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('producto_id')
                            ->label('Producto')
                            ->options(Producto::where('activo', true)->pluck('nombre', 'id'))
                            ->searchable()->required()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $producto = Producto::find($state);
                                if ($producto) {
                                    $set('precio_unitario', $producto->precio_venta);
                                    $set('nombre_producto', $producto->nombre);
                                }
                            })->live(),
                        Forms\Components\Hidden::make('nombre_producto'),
                        Forms\Components\TextInput::make('precio_unitario')->numeric()->prefix('$')->required()->label('Precio'),
                        Forms\Components\TextInput::make('cantidad')->numeric()->required()->default(1)->minValue(1),
                        Forms\Components\TextInput::make('descuento')->numeric()->prefix('$')->default(0),
                        Forms\Components\TextInput::make('subtotal')->numeric()->prefix('$')->readOnly(),
                    ])->columns(5)->live()
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::calcularTotales($get, $set)),
            ]),
            Forms\Components\Section::make('Totales')->columns(3)->schema([
                Forms\Components\TextInput::make('subtotal')->numeric()->prefix('$')->readOnly(),
                Forms\Components\TextInput::make('descuento')->numeric()->prefix('$')->default(0)->live()
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::calcularTotales($get, $set)),
                Forms\Components\TextInput::make('impuesto')->numeric()->prefix('$')->default(0)->readOnly(),
                Forms\Components\TextInput::make('total')->numeric()->prefix('$')->readOnly(),
                Forms\Components\TextInput::make('cambio')->numeric()->prefix('$')->readOnly(),
            ]),
        ]);
    }

    protected static function calcularTotales(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = collect($items)->sum(fn($i) => ($i['precio_unitario'] ?? 0) * ($i['cantidad'] ?? 1) - ($i['descuento'] ?? 0));
        $descuento = (float)($get('descuento') ?? 0);
        $total = $subtotal - $descuento;
        $set('subtotal', round($subtotal, 2));
        $set('total', round($total, 2));
        $set('cambio', max(0, round(($get('monto_pagado') ?? 0) - $total, 2)));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folio')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Fecha')->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre')->label('Cliente')->default('Público general'),
                Tables\Columns\TextColumn::make('user.name')->label('Cajero'),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'view'   => Pages\ViewVenta::route('/{record}'),
        ];
    }
}
