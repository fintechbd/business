<?php

namespace Fintech\Business;

use Fintech\Business\Commands\BusinessCommand;
use Fintech\Business\Commands\InstallCommand;
use Fintech\Business\Providers\RepositoryServiceProvider;
use Fintech\Business\Providers\RouteServiceProvider;
use Fintech\Core\Traits\RegisterPackageTrait;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    use RegisterPackageTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->packageCode = 'business';

        $this->mergeConfigFrom(
            __DIR__.'/../config/business.php', 'fintech.business'
        );

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->injectOnConfig();

        $this->publishes([
            __DIR__.'/../config/business.php' => config_path('fintech/business.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'business');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/business'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'business');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/business'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                BusinessCommand::class,
            ]);
        }
    }
}
