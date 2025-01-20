<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->maxContentWidth('full')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandName(env('APP_NAME'))
            ->brandLogo(function () {
                if (request()->route()->getName() == 'filament.admin.auth.login') {
                    return asset('images/Database.png');
                }

                if (request()->route()->getName() == 'livewire.update') {
                    try {
                        // Decode the incoming request data safely
                        $content = request()->getContent();
                        $decodedContent = json_decode($content, true);

                        // Ensure components key exists and is an array
                        if (isset($decodedContent['components'][0]['snapshot'])) {
                            // Decode the snapshot JSON
                            $snapshot = json_decode($decodedContent['components'][0]['snapshot']);

                            // Check if memo->path exists in the snapshot
                            if (isset($snapshot->memo->path) && $snapshot->memo->path == 'admin/login') {
                                return asset('images/database.png');
                            }
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                return asset('images/dasboard33.png');
            })
            ->brandLogoHeight(function () {
                if (request()->route()->getName() == 'filament.admin.auth.login') {
                    return '6rem';
                }

                if (request()->route()->getName() == 'livewire.update') {
                    try {
                        // Decode the incoming request data safely
                        $content = request()->getContent();
                        $decodedContent = json_decode($content, true);

                        // Ensure components key exists and is an array
                        if (isset($decodedContent['components'][0]['snapshot'])) {
                            // Decode the snapshot JSON
                            $snapshot = json_decode($decodedContent['components'][0]['snapshot']);

                            // Check if memo->path exists in the snapshot
                            if (isset($snapshot->memo->path) && $snapshot->memo->path == 'admin/login') {
                                return '6rem';
                            }
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                return '5rem';
            })
            ->favicon(asset('images/database.png'))
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
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
            ]);
    }
}
