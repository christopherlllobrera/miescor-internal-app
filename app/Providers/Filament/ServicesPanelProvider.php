<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Dashboard\CBDIDashboard;
use App\Filament\Pages\Dashboard\HRDashboard;
use App\Filament\Pages\Dashboard\UserDashboard;
use App\Filament\Pages\MyProfile;
use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ServicesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('services')
            ->path('services')
            ->login(Login::class)
            ->profile()
            ->spa()
            ->favicon(secure_asset('favicon-official-1.png'))
            ->brandName('IAM')
            ->brandLogoHeight('3rem')
            ->darkModeBrandLogo(fn () => view('filament.customization.logodarkmode'))
            ->brandLogo(fn () => view('filament.customization.logo'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->brandName('IAM')
                    ->recoverable()
                    ->recoveryCodeCount(10),
                EmailAuthentication::make()
                    ->codeExpiryMinutes(2),

            ])
            ->topbar(false)
            ->simplePageMaxContentWidth(Width::Small)
            ->databaseNotifications()
            ->globalSearch(false)
            ->sidebarCollapsibleOnDesktop()
            ->defaultThemeMode(ThemeMode::Light)
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                UserDashboard::class,
                CBDIDashboard::class,
                HRDashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Payroll Self Service'),
                NavigationGroup::make()
                    ->label('Employee Portal'),
                NavigationGroup::make()
                    ->label('Data Management'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsed(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label('Profile')
                    ->url(fn (): string => MyProfile::getUrl())
                    ->icon('heroicon-m-user'),
            ]);
    }
}
