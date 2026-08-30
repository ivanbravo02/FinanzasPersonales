<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-user-group'; }
    public static function getNavigationGroup(): ?string { return 'Administración'; }
    public static function getModelLabel(): string { return 'Usuario'; }
    public static function getPluralModelLabel(): string { return 'Usuarios'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required()->label('Nombre'),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('role_id')
                ->relationship('role', 'nombre')->required()->label('Rol'),
            Forms\Components\TextInput::make('password')
                ->password()->dehydrateStateUsing(fn($s) => Hash::make($s))
                ->dehydrated(fn($s) => filled($s))
                ->required(fn(string $context) => $context === 'create')
                ->label('Contraseña'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role.nombre')->badge()->label('Rol'),
                Tables\Columns\TextColumn::make('created_at')->date('d/m/Y')->label('Creado'),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
