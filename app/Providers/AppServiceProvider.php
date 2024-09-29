<?php

namespace App\Providers;

use App\Interfaces\UserInterface;
use App\ServiceInterfaces\PaymentInterface;
use App\ServiceInterfaces\SingleContentInterface;
use App\Services\PaymentService;
use App\Services\SingleContentService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserInterface::class, UserService::class);
        $this->app->singleton(SingleContentInterface::class, SingleContentService::class);
        $this->app->singleton(PaymentInterface::class, PaymentService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
