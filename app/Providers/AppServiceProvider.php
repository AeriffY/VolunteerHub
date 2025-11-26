<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. 引入 Paginator 类
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
        // 2. 告诉 Laravel Paginator 使用 Bootstrap 5 的分页模板
        Paginator::useBootstrapFive();
    }
}
