<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    public function boot(Request $request): void
    {
        // if (config('app.env') === 'production') {

        //     // Cek header X-Forwarded-Proto dari Cloudflare / proxy
        //     if ($request->header('X-Forwarded-Proto') === 'https' || $request->secure()) {
        //         URL::forceScheme('https');
        //     }
        // }
    }
}
