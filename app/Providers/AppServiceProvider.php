<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        // Ide for developer
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        if (!$this->app->runningInConsole()) {
            $themes = 'default';
            view()->share('app_site_title', config('app.name'));
            view()->share('app_site_theme', $themes);
            $viewThemes = 'frontend.themes.' . $themes;
            view()->share('view_themes', $viewThemes);
        }
    }
}
