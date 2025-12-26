<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverview;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Settings\GeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
// use Filament\Pages\Dashboard;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\Bookings\BookingResource;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Outfit')
            ->brandName(fn(GeneralSettings $settings) => $settings->site_name ?? 'Boston Logan')
            ->brandLogo(
                fn(GeneralSettings $settings) => $settings->site_logo
                    ? Storage::url($settings->site_logo)
                    : null
            )
            ->darkModeBrandLogo(
                fn(GeneralSettings $settings) => $settings->site_logo
                    ? Storage::url($settings->site_logo)
                    : null
            )
            ->brandLogoHeight('3rem')
            ->sidebarCollapsibleOnDesktop()
            ->spa()

            ->profile()

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                StatsOverview::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            if (Auth::check() && !Auth::user()->hasRole('super_admin')) {
                $currentUrl = request()->url();
                $targetUrl = BlogPostResource::getUrl('index');
                if ($currentUrl !== $targetUrl && !request()->routeIs('filament.admin.auth.*')) {
                    config(['filament.home_url' => $targetUrl]);
                    if (request()->path() === 'admin') {
                        redirect()->to($targetUrl)->send();
                    }
                }
            }
        });
    }
}
