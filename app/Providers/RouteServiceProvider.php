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
    public const HOME = '/user/dashboard';
    public const ADMIN_DASHBOARD = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'maintenance'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'maintenance'])
                ->prefix('user')
                ->name('user.')
                ->group(base_path('routes/user.php'));

            Route::middleware(['web'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web'])
                ->namespace('Admin')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/general.php'));

            Route::middleware(['web'])
                ->namespace('Admin')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/report.php'));

            Route::middleware(['web'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/mail.php'));
        });

        Route::get('maintenance-mode', 'App\Http\Controllers\WebController@maintenance')->name('maintenance');
    }
}
