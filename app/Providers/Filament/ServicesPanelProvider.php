<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
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
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

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
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Group Profile')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    // ->canAccess(fn () => auth()->user()->id === 1)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowEmailForm()
                    ->shouldShowAvatarForm()
                    ->shouldShowMultiFactorAuthentication(),
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label('Profile')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
                // If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                // ->visible(function (): bool {
                //     return auth()->user()->company()->exists();
                // }),
            ]);
    }
}
