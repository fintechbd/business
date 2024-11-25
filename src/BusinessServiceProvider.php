<?php

namespace Fintech\Business;

use Fintech\Business\Commands\InstallCommand;
use Fintech\Business\Commands\ServiceJsonFieldFixCommand;
use Fintech\Business\Providers\RepositoryServiceProvider;
use Fintech\Core\Traits\Packages\RegisterPackageTrait;
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

        $this->publishes([
            __DIR__.'/../database/seeders/currency_rates.json' => database_path('seeders/currency_rates.json'),
        ], 'currency-rate-stub');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'business');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/business'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'business');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/business'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                ServiceJsonFieldFixCommand::class,
            ]);
        }
    }
}
