<?php

namespace App\Providers;

use App\Services\MovieService;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MovieService::class, MovieService::class);

        $this->app->bind(PostService::class, PostService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            Model::preventLazyLoading(!app()->isProduction());
        }
    }
}
