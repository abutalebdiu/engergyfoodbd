<?php

namespace App\Providers;


use App\Constants\Status;
use App\Models\Setting\Frontend;
use App\Models\AdminNotification;
use Illuminate\Pagination\Paginator;
use App\Models\Setting\SupportTicket;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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

        $viewShare['general'] = gs();
        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'pendingSupportCount' => SupportTicket::whereIN('status', [Status::SUPPORT_OPEN, Status::SUPPORT_REPLY])->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        Paginator::useBootstrapFour();
    }
}
