<?php

namespace App\Providers;

use App\Service\AuthorizeService;
use App\Service\OneDrive;
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
        $account = setting('account');
        $this->app->bind('OneDrive/Request', static function () use ($account) {
            return OneDrive::init()->bind($account);
        });
        $this->app->bind('OneDrive/Authorize', static function () use ($account) {
            return AuthorizeService::init()->bind($account);
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
