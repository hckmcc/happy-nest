<?php

namespace App\Providers;

use App\Services\YouGileService;
use App\Services\YukassaService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(YouGileService::class, function (){
            return new YouGileService(config('services.yougile.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('auth.modal', \App\Http\Middleware\CheckAuthWithModal::class);
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);
    }
}
