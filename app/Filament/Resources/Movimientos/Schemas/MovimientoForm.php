<?php

namespace App\Filament\Resources\Movimientos\Schemas;

use App\Models\Categoria;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MovimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuarios')
                    ->required()
                    ->options(User::all()->pluck('name','id')),
                Select::make('categoria_id')
                    ->required()
                    ->label('Categorias')
                    ->options(Categoria::all()->pluck('nombre','id')),
                Select::make('tipo')
                    ->options(['ingreso' => 'Ingreso', 'gasto' => 'Gasto'])
                    ->required(),
                TextInput::make('monto')
                    ->label('Monto')
                    ->required()
                    ->numeric(),
                RichEditor::make('descripcion')
                    ->label('Descripcion')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->directory('movimientos'),
                DatePicker::make('fecha')
                    ->required(),
            ]);
    }
}
