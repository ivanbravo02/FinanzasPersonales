<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Cajero\Widgets\CajeroStatsWidget;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CajeroPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('cajero')
            ->path('cajero')
            ->login()
            ->colors(['primary' => Color::Emerald])
            ->discoverResources(in: app_path('Filament/Cajero/Resources'), for: 'App\\Filament\\Cajero\\Resources')
            ->discoverPages(in: app_path('Filament/Cajero/Pages'), for: 'App\\Filament\\Cajero\\Pages')
            ->discoverWidgets(in: app_path('Filament/Cajero/Widgets'), for: 'App\\Filament\\Cajero\\Widgets')
            ->pages([Dashboard::class])
            ->widgets([
                AccountWidget::class,
                CajeroStatsWidget::class,
            ])
            ->authGuard('web')
            ->authMiddleware([Authenticate::class])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
    }
}
