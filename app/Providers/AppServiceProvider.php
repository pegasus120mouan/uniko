<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        View::composer('layout.main', function ($view) {
            $count = 0;

            if (Schema::hasTable('orders')) {
                $count = Order::query()
                    ->where('status', 'pending_confirmation')
                    ->count();
            }

            $view->with('pendingOrdersCount', $count);
        });
    }
}
