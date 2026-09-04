<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard as CustomDashboard;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => view('filament.custom-styles')->render()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): string => view('filament.topbar-cv-button')->render()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => view('filament.simple-back-button')->render()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): string => view('filament.sidebar-toggle-button')->render()
        );
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->brandName('Portfolio Admin')
            ->font('Outfit')
            ->spa()                              // SPA mode: pindah halaman tanpa reload/kedip
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('14.5rem')
            ->collapsedSidebarWidth('4.5rem')
            ->globalSearch()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
                'info' => Color::Cyan,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
            ])
            ->userMenuItems([
                'account' => MenuItem::make()
                    ->label('Pengaturan Akun & Keamanan')
                    ->url(url('/admin/profile'))
                    ->icon('heroicon-o-user-circle'),
                MenuItem::make()
                    ->label('Lihat Website Publik')
                    ->url(url('/'))
                    ->icon('heroicon-o-globe-alt')
                    ->openUrlInNewTab(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                CustomDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
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
            ->profile(\App\Filament\Pages\Auth\CustomEditProfile::class)
            ->multiFactorAuthentication([
                AppAuthentication::make()->recoverable(),
                EmailAuthentication::make(),
            ], isRequired: false)
            ->plugin(
                FilamentSocialitePlugin::make()
                    ->providers([
                        Provider::make('google')
                            ->label('GOOGLE')
                            ->color(Color::hex('#DB4437'))
                            ->with(['prompt' => 'select_account'])
                            ->stateless(),
                    ])
                    ->authorizeUserUsing(function (FilamentSocialitePlugin $plugin, SocialiteUserContract $oauthUser) {
                        $email = trim($oauthUser->getEmail());

                        \Illuminate\Support\Facades\Log::info('Google OAuth authorize attempt', [
                            'email_from_google' => $email,
                        ]);

                        return \App\Models\User::where('email', $email)
                            ->where('role', 'admin')
                            ->exists();
                    })
                    ->registration(false)
                    ->resolveUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        $email = trim($oauthUser->getEmail());

                        \Illuminate\Support\Facades\Log::info('Google OAuth login attempt', [
                            'email_from_google' => $email,
                        ]);

                        return \App\Models\User::where('email', $email)
                            ->where('role', 'admin')
                            ->first();
                    })
            );
    }
}
