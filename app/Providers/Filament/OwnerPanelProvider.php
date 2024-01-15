<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class OwnerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('owner')
            ->path('owner')
            ->colors([
                'danger'=>Color::Red,
                'gray'=>Color::Slate,
                'info'=>Color::Blue,
                'primary' => Color::Indigo,
                'success'=>Color::Emerald,
                'warning'=>Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Owner/Resources'), for: 'App\\Filament\\Owner\\Resources')
            ->discoverPages(in: app_path('Filament/Owner/Pages'), for: 'App\\Filament\\Owner\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Owner/Widgets'), for: 'App\\Filament\\Owner\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems([
                // NavigationItem::make('Manager Settings')
                // ->url('/static')
                // ->icon('heroicon-o-cog')
                // ->group('Manager Pages')
                // ->sort(4),

                // NavigationItem::make('Employee Portal')
                // ->url('/employee')
                // ->icon('heroicon-o-user-circle')
                // ->group('Manager Pages')
                // ->sort(5),

                // NavigationItem::make('Manager Portal')
                // ->url('/admin')
                // ->icon('heroicon-o-academic-cap')
                // ->group('Manager Pages')
                // ->sort(6),

                NavigationItem::make('Dashboard')
                ->url('/dashboard')
                ->icon('heroicon-o-home')
                ->group('Manager Pages')
                ->sort(6),
            ])
            ->sidebarCollapsibleOnDesktop();
    }
}
