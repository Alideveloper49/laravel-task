<?php

namespace App\Providers;

use App\Repositories\Interface\StockPriceRepositoryInterface;
use App\Repositories\StockPriceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StockPriceRepositoryInterface::class, StockPriceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
