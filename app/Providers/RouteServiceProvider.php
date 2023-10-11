<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {

            // Limite de requisições para usuário logado maior
            return empty($request->user()->id) ?
                Limit::perMinute(30)->by($request->ip()) :
                Limit::perMinute(50)->by($request->user()->id);
        });

        $this->routes(function () {

            // API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/auction.php'));
        });
    }
}
