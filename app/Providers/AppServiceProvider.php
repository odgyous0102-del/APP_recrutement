<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('partials.header', function ($view) {
            if (auth()->check()) {
                $notifications = auth()->user()->unreadNotifications()->orderBy('created_at', 'desc')->get();
                $view->with('unreadNotifications', $notifications);
            }
        });

        // Partager également avec le dashboard admin si nécessaire
        view()->composer('admin.*', function ($view) {
            if (auth()->check()) {
                $notifications = auth()->user()->unreadNotifications()->orderBy('created_at', 'desc')->get();
                $view->with('unreadNotifications', $notifications);
            }
        });
    }
}
