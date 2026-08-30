<?php

namespace App\Filament\Cajero\Pages;

use App\Models\CorteCaja;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CorteCajaPage extends Page
{
    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-calculator'; }
    public static function getNavigationLabel(): string { return 'Corte de caja'; }
    public static function getSlug(?\Filament\Panel $panel = null): string { return 'corte-caja'; }

    public function getView(): string { return 'filament.cajero.pages.corte-caja'; }
    public function getTitle(): string { return 'Corte de caja'; }

    public ?CorteCaja $corteActivo = null;

    public function mount(): void
    {
        $this->corteActivo = CorteCaja::where('user_id', auth()->id())
            ->where('estado', 'abierto')->latest()->first();
    }

    protected function getHeaderActions(): array
    {
        if (!$this->corteActivo) {
            return [
                Action::make('abrir_caja')
                    ->label('Abrir caja')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->form([
                        TextInput::make('fondo_inicial')
                            ->label('Fondo inicial ($)')
                            ->numeric()->required()->minValue(0)->default(0),
                    ])
                    ->action(function (array $data) {
                        CorteCaja::create([
                            'user_id'       => auth()->id(),
                            'fondo_inicial' => $data['fondo_inicial'],
                            'estado'        => 'abierto',
                            'abierto_en'    => now(),
                        ]);
                        Notification::make()->title('Caja abierta correctamente')->success()->send();
                        $this->mount();
                        $this->dispatch('$refresh');
                    }),
            ];
        }

        return [
            Action::make('cerrar_caja')
                ->label('Cerrar caja')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    TextInput::make('efectivo_contado')
                        ->label('Efectivo contado en caja ($)')
                        ->numeric()->required()->minValue(0),
                    Textarea::make('notas')->label('Notas del cierre'),
                ])
                ->action(function (array $data) {
                    $corte = $this->corteActivo;
                    $corte->calcularTotales();
                    $corte->efectivo_contado = $data['efectivo_contado'];
                    $corte->diferencia = $data['efectivo_contado'] - ($corte->fondo_inicial + $corte->total_efectivo);
                    $corte->notas = $data['notas'] ?? null;
                    $corte->estado = 'cerrado';
                    $corte->cerrado_en = now();
                    $corte->save();

                    Notification::make()
                        ->title('Caja cerrada')
                        ->body('Diferencia: $' . number_format($corte->diferencia, 2))
                        ->success()->send();

                    $this->corteActivo = null;
                    $this->dispatch('$refresh');
                }),
        ];
    }
}
