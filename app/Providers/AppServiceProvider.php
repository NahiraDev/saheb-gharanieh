<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // @fa(12)     → ۱۲
        // @price($p)  → ۱۸۵٬۰۰۰ تومان
        Blade::directive('fa', fn ($expression) => "<?php echo e(\\App\\Support\\Persian::digits({$expression})); ?>");
        Blade::directive('price', fn ($expression) => "<?php echo e(\\App\\Support\\Persian::price({$expression})); ?>");
    }
}
