<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Seo;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            // $settings = app(GeneralSettings::class);
            if (config('app.env') !== 'local') {
                URL::forceScheme('https');
            }
    }
}
