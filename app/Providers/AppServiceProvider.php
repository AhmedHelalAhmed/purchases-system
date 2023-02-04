<?php

namespace App\Providers;

use App\Enums\RolesEnum;
use App\Services\AdminService;
use App\Services\CustomerService;
use App\Services\DeleteUserInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DeleteUserInterface::class, function () {
            return new ([
                RolesEnum::ADMIN->value => AdminService::class,
                RolesEnum::CUSTOMER->value => CustomerService::class,
            ][Route::current()->parameter('user')->originalRole]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
