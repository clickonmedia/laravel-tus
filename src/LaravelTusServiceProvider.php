<?php

namespace Clickonmedia\LaravelTus;

use Illuminate\Support\ServiceProvider;

class LaravelTusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-tus');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-tus');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-tus.php'),
            ], 'config');

            // $this->publishes([
            //     __DIR__.'/../database/migrations/create_tus_cache_table.php.stub' => $this->getMigrationFileName('create_tus_cache_table.php'),
            // ], 'migrations');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-tus'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-tus'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-tus'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-tus');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-tus', function () {
            return new LaravelTus;
        });
    } 
    
    /**
    * Returns existing migration file if found, else uses the current timestamp.
    *
    * @return string
    */
   protected function getMigrationFileName($migrationFileName): string
    {
       $timestamp = date('Y_m_d_His');

       $filesystem = $this->app->make(Filesystem::class);

       return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
           ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
               return $filesystem->glob($path.'*_'.$migrationFileName);
           })
           ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
           ->first();
   }
}
